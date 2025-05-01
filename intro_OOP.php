<?php
#Для запуска ввести в трминале "С:\xampp\htdocs\intro_OOP.php"

class Book {
    
    public $name;
    public $author;
    public $dateOfPublication;

    public function __construct($name, $author, $dateOfPublication){
        $this->setName($name);
        $this->setAuthor($author);
        $this->setDateOfPublication($dateOfPublication);
    }

    public function setName($name){
        $this->name = $name;
    }

    public function setAuthor($author){
        $this->author = $author;
    }

    public function setDateOfPublication($dateOfPublication){
        $this->dateOfPublication = $dateOfPublication;
    }

    public function isAvailable(){

    }

    public function assignToUser(){

    }
    
    public function returnBook(){

    }
}

class User {

    public $name;
    public $books = [];

}

class Library {
    
    private $books = [];
    
    public function addBook(Book $book){
        $this->books[] = $book;
    }

    public function getBooks(){
        return $this->books;
    }
}

class BookInputHandler {
    
    public static function inputBooks(){
    
        $books = [];
    
        while(true){
            $name = readline("Введите название книги (или \"стоп\" для завершения): ");
            if($name == "стоп"){
                break;
            }
            $author = readline("Автор: ");
            $dateOfPublication = readline("Год издания: ");
            $books[] = [
                'name' => $name,
                'author' => $author,
                'dateOfPublication' => $dateOfPublication
            ];
        }
    
        return $books;
    }
}

echo "Добро пожаловать в нашу библиотеку\n\n";
echo "---Давайте добавим ваши книги---\n\n";

$library = new Library();
$inputBooks = BookInputHandler::inputBooks();

foreach($inputBooks as $bookData){
    $book = new Book($bookData['name'], $bookData['author'], $bookData['dateOfPublication']);
    $library->addBook($book);
}


?>