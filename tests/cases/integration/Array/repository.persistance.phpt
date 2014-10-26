<?php

/**
 * @testCase
 * @dataProvider ../../../databases.ini
 */

namespace Nextras\Orm\Tests\Integrations;

use Mockery;
use Nextras\Orm\Tests\Author;
use Nextras\Orm\Tests\Book;
use Nextras\Orm\Tests\Publisher;
use Nextras\Orm\Tests\TestCase;
use Tester\Assert;


$dic = require_once __DIR__ . '/../../../bootstrap.php';


class RepostiroyPersistanceTest extends TestCase
{

	public function testComplexPersistanceTree()
	{
		$authors = [];
		for ($i = 0; $i < 20; $i += 1) {
			$author = new Author();
			$author->name = 'Author ' . $i;
			$authors[] = $author;
		}

		$publishers = [];
		for ($i = 0; $i < 20; $i += 1) {
			$publisher = new Publisher();
			$publisher->name = 'Publisher ' . $i;
			$publishers[] = $publisher;
		}

		$books = [];
		for ($i = 0; $i < 20; $i += 1) {
			for ($y = 0; $y < 20; $y += 1) {
				$book = new Book();
				$this->orm->books->attach($book);
				$book->title = "Book $i-$y";

				$book->author = $authors[$i];
				$book->publisher = $publishers[$y];

				$books[] = $book;
			}
		}

		$this->orm->authors->persistAndFlush($authors[0]);

		foreach ($authors as $author) {
			Assert::true($author->isPersisted());
		}
		foreach ($publishers as $publisher) {
			Assert::true($publisher->isPersisted());
		}
		foreach ($books as $book) {
			Assert::true($book->isPersisted());
		}
	}


	public function testOneHasOneDirected()
	{
		$author = new Author();
		$author->name = 'The Imp';
		$author->web = 'localhost';
		$author->born = '2000-01-01 12:12:12';

		$this->orm->authors->attach($author);

		$publisher = new Publisher();
		$publisher->name = 'Valyria';

		$book = new Book();
		$book->author = $author;
		$book->title = 'The Wall';
		$book->publisher = $publisher;
		$book->translator = $author;

		$book2 = new Book();
		$book2->author = $author;
		$book2->title = 'The Wall II';
		$book2->publisher = $publisher;
		$book2->previousPart = $book;

		$this->orm->authors->persistAndFlush($author);
		Assert::true($book->isPersisted());
		Assert::true($book2->isPersisted());
		Assert::same($book2, $book->nextPart);
	}

}


$test = new RepostiroyPersistanceTest($dic);
$test->run();
