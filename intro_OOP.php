<?php
#Для запуска ввести в трминале "C:\xampp\htdocs\intro_OOP.php"
declare(strict_types=1);

abstract class PrintedMaterial {
    protected string $title;
    protected string $author;
    protected int $year;

    public function __construct(string $title, string $author, int $year) {
        $this->title = $title;
        $this->author = $author;
        $this->year = $year;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getInfo(): string {
        return "{$this->title} ({$this->year}) by {$this->author}";
    }
}

interface Loanable {
    public function borrow(User $user): bool;
    public function returnBook(): void;
    public function isAvailable(): bool;
}

class Book extends PrintedMaterial implements Loanable{
    
    private ?User $borrowedBy = null;

    public function borrow(User $user): bool {
        if ($this->borrowedBy !== null) {
            return false;
        }
        $this->borrowedBy = $user;
        return true;
    }

    public function returnBook(): void {
        $this->borrowedBy = null;
    }

    public function isAvailable(): bool {
        return $this->borrowedBy === null;
    }

    public function getBorrower(): ?User {
        return $this->borrowedBy;
    }
}

class User {

    private string $name;
    private array $borrowedBooks = [];

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }

    public function borrowBook(Book $book): bool {
        if ($book->borrow($this)) {
            $this->borrowedBooks[] = $book;
            return true;
        }
        return false;
    }

    public function returnBook(Book $book): void {
        $book->returnBook();
        // Удалить книгу из списка взятых
        foreach ($this->borrowedBooks as $index => $b) {
            if ($b === $book) {
                unset($this->borrowedBooks[$index]);
                $this->borrowedBooks = array_values($this->borrowedBooks);
            }
        }
    }

    public function getBorrowedBooks(): array {
        return $this->borrowedBooks;
    }
}

class Library {
    private array $books = [];
    private array $users = [];

    public function addBook(Book $book): void {
        $this->books[] = $book;
    }

    public function registerUser(User $user): void {
        $this->users[] = $user;
    }

    public function getBooks(): array {
        return $this->books;
    }

    public function getUsers(): array {
        return $this->users;
    }
}

#
#
# НИже реализуются код без классов
#
#

echo "Добро пожаловать в нашу библиотеку\n\n";
echo "---Давайте добавим ваши книги---\n\n";

$library = new Library();

while(true){
    $titleOfBook = readline("Введите название вашей книги (или \"стоп\" для завершения): ");
    
    if($titleOfBook === "стоп"){
        break;
    }

    $authorOfBook = readline("Введите автора: ");
    $year = (int)readline("Введите год издания: ");

    $nBook = new Book($titleOfBook, $authorOfBook, $year);
    $library->addBook($nBook);
}

echo "Давайте теперь зарегистрируем пользователей\n\n";

while(true){
    $nameOfUser = readline("Введите имя пользователя (или \"стоп\" для завершения): ");

    if($nameOfUser === "стоп"){
        break;
    }

    $nUser = new User($nameOfUser);
    $library->registerUser($nUser);
}

echo "\n\n--- Действия ---\n\n";

echo "выберите из следующих пользователей какой вам нужен, чтобы подобрать для него нужные книги\n\n";

$users = $library->getUsers();
$books = $library->getBooks();

while(true){

    $stopper = readline("Хотите изменить владения пользователей книгами? (y/n): ");
    if($stopper === "y"){
        foreach($users as $user){

            $choiceOfUser = readline("Выбираете ли этого пользователя: " . $user->getName() . " ? (y/n): ");
            if($choiceOfUser === "y"){
                
                $choiceOfAction = (string)readline("Вы хотите взять(1) или вернуть(2) книгу: ");
                if($choiceOfAction === "1"){
                    foreach($books as $book){
        
                        $choiceOFBook = readline("Выбираете ли эту книгу: " . $book->getInfo() . " ? (y/n): ");
                        if($choiceOFBook === "y"){
                            if(($user->borrowBook($book)) == true){
                                echo "Пользователю " . $user->getName() . " была выдана книга: " . $book->getInfo() . "\n";
                            }else{
                                echo $user->getName() . " не может взять книгу " . $book->getInfo() . ". Книга уже выдана кому-то другому.\n";
                            }
                        }
                    }
                }elseif($choiceOfAction === "2"){
                    foreach($books as $book){

                        $choiceOFBook = readline("Выбираете ли эту книгу: " . $book->getInfo() . " ? (y/n): ");
                        if($choiceOFBook === "y"){
                            $user->returnBook($book);
                            echo "Пользователем " . $user->getName() . " была возвращена книга: " . $book->getInfo() . "\n";
                        }
                    }
                }
            }
        }
    } else{
        break;
    }
}

echo "\n\n--- Статус библиотеки ---\n\n";

echo "Книги в библиотеке:\n\n";

foreach($books as $book){
    if($book->isAvailable()){
        echo "Книга \"" . $book->getTitle() . "\" - свободна\n";
    }else{
        echo "Книга \"" . $book->getTitle() . "\" на руках у " . $book->getBorrower()->getName() . "\n";
    }
}


echo "Книги на руках у пользователей:\n\n";

foreach($users as $user){
    foreach($user->getBorrowedBooks() as $book){
        echo $user->getName() . ": " . $book->getTitle() . "\n";
    }
}
?>