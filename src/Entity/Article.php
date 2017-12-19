<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

     /**
     * @ORM\Column(name="date_add", type="datetime")
     */
    private $dateAdd;     

    /**
     * @ORM\Column(name='title',type="string", length=255)
     */
    private $title;

     /**
     * @ORM\Column(name="content" ,type="string")
     */
    private $content;

    
}
