<?php
class ProductException extends Exception
{
}
class Product
{

    private $id;
    private $title;
    private $status;
    private $imageSrc;
    private $price;
    private $color;
    private $size;
    private $description;
    public function __construct($id, $title, $status, $imageSrc, $price, $color, $size, $description)
    {
        $this->id = $id;
        $this->title = $title;
        $this->status = $status;
        $this->imageSrc = $imageSrc;
        $this->price = $price;
        $this->color = $color;
        $this->size = $size;
        $this->description = $description;
    }
    //* getter
    public function getId()
    {
        return $this->id;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function getImageSrc()
    {
        return $this->imageSrc;
    }
    public function getPrice()
    {
        return $this->price;
    }
    public function getColor()
    {
        return $this->color;
    }
    public function getSize()
    {
        return $this->size;
    }
    public function getDescription()
    {
        return $this->description;
    }

    public function returnProductArray()
    {
        $product = array();
        $product['id'] = $this->id;
        $product['title'] = $this->title;
        $product['status'] = $this->status;
        $product['imgSrc'] = $this->imageSrc;
        $product['price'] = $this->price;
        $product['color'] = $this->color;
        $product['size'] = $this->size;
        $product['description'] = $this->description;
        return $product;
    }

}
