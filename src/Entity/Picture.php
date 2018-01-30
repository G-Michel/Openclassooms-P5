<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Intervention\Image\ImageManager;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @ORM\Entity(repositoryClass="App\Repository\PictureRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Picture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    /**
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
    * @var UploadedFile
    */
    private $file;

    private $tempFilename;

    /**
    * @var ImageManager
    */
    private $manager;

    public function __construct ()
    {
      $this->manager = new ImageManager();
    }

  /**
   * @ORM\PrePersist()
   * @ORM\PreUpdate()
   */
  public function preUpload()
  {
    // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
    if (null === $this->getFile()) {
      return;
    }

    // Le nom du fichier est son id, on doit juste stocker également son extension
    // Pour faire propre, on devrait renommer cet attribut en « extension », plutôt que « url »
    $this->setUrl($this->getFile()->guessExtension());

    // Et on génère l'attribut alt de la balise <img>, à la valeur du nom du fichier sur le PC de l'internaute
    if (null === $this->getAlt()) {
      $this->setAlt($this->getFile()->getClientOriginalName());
    }
  }

  /**
   * @ORM\PostPersist()
   * @ORM\PostUpdate()
   */
  public function upload()
  {
    // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
    if (null === $this->getFile()) {
      return;
    }

    // Si on avait un ancien fichier, on le supprime
    if (null !== $this->tempFilename) {
      $oldFile = $this->getUploadRootDir().'/'.$this->getId().'.'.$this->tempFilename;
      if (file_exists($oldFile)) {
        unlink($oldFile);
      }
    }

    // to finally create image thumnail and fullHd
    $this->manager->make($this->getFile())
                     ->resize(160, 100)
                     ->interlace(true)
                     ->save($this->getUploadRootDir().'/thumbnails/'.$this->getId().'.'.$this->getUrl());
    // to finally create image thumnail and fullHd
    $this->manager->make($this->getFile())
                     ->resize(1920, 1080)
                     ->interlace(true)
                     ->save($this->getUploadRootDir().'/fullHd/'.$this->getId().'.'.$this->getUrl());
  unset($this->file);
  }

  /**
   * @ORM\PreRemove()
   */
  public function preRemoveUpload()
  {
    // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
    $this->tempFilename = $this->getUploadRootDir().'/'.$this->getId().'.'.$this->getUrl();
  }

  /**
   * @ORM\PostRemove()
   */
  public function removeUpload()
  {
    // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
    if (file_exists($this->tempFilename)) {
      // On supprime le fichier
      unlink($this->tempFilename);
    }
  }

  public function getUploadDir()
  {
    // On retourne le chemin relatif vers l'image pour un navigateur (relatif au répertoire /web donc)
    return 'uploads/img';
  }

  protected function getUploadRootDir()
  {
    // On retourne le chemin relatif vers l'image pour notre code PHP
    return dirname(dirname(__DIR__)).'/public/'.$this->getUploadDir();
  }

  public function getWebPath($size = 'sm')
  {
    if ($size === 'hd') {
      return $this->getUploadDir().'/fullHd/'.$this->getId().'.'.$this->getUrl();
    }
    return $this->getUploadDir().'/thumbnails/'.$this->getId().'.'.$this->getUrl();
  }

  /**
   * @return mixed
   */
  public function getId()
  {
      return $this->id;
  }


  /**
   * @return mixed
   */
  public function getAlt()
  {
      return $this->alt;
  }

  /**
   * @param mixed $alt
   *
   * @return self
   */
  public function setAlt($alt)
  {
      $this->alt = $alt;
  }

  /**
   * @return mixed
   */
  public function getUrl()
  {
      return $this->url;
  }

  /**
   * @param mixed $url
   *
   * @return self
   */
  public function setUrl($url)
  {
      $this->url = $url;
  }

  /**
   * @return UploadedFile
   */
  public function getFile()
  {
    return $this->file;
  }

  /**
   * @param UploadedFile $file
   */
  public function setFile(UploadedFile $file = null)
  {
    $this->file = $file;

    // On vérifie si on avait déjà un fichier pour cette entité
    if (null !== $this->getUrl()) {
      // On sauvegarde l'extension du fichier pour le supprimer plus tard
      $this->tempFilename = $this->getUrl();

      // On réinitialise les valeurs des attributs url et alt
      $this->setUrl(null);
      $this->setAlt(null);
    }
  }

}
