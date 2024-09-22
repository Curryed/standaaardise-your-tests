<div align="center">
  <img alt="A cute dinosaur" src="Illustration_2.png" height="200"/>
</div>

# StandAAArdise

Hello! If you found this repository, you are probably looking for a way to standardize your projects, and that's a good thing!

This repository does nothing. Literally nothing. It's a place where you can find the project I used to prepare my talk about the AAA pattern.

## What is the AAA pattern?

Well, all the answers can be found in the [slides](./slides/standaaardise-your-tests.pptx) of my talk! (todo: add url when the video is out).

## How to use this repository?

I used this repository for a demo for this talk!

The idea is to explain how we could standardize our tests.
A part of it is to use the same language: using the same vocabulary to talk about the same things, does Unit test mean the same to you and me?
Also use the same logic to write our test in a similar way. And that's when the pattern AAA comes to mind.

Even though the pattern seems strict, you can add a bit of flexibility to what you write, for example when expecting exceptions with PHPUnit (see `BookDinosaurTest::it_throws_an_exception_when_booking_in_the_past`).

If you want to play with the AAA pattern, you can rewrite any part of this app. Or even be crazy and add things up.

### Install

```bash
./bin/composer install
```

### Run tests

```bash
./bin/phpunit
```

You can look at the tests in the [tests](./tests) directory.

## Resources

* About AAA :
    * [3A – Arrange, Act, Assert](https://xp123.com/3a-arrange-act-assert/)
    * [Unit Testing and the Arrange, Act and Assert (AAA) Pattern](https://medium.com/@pjbgf/title-testing-code-ocd-and-the-aaa-pattern-df453975ab80)
    * [FR] [Le pattern AAA, qu'est-ce que c'est ?](https://www.hubvisory.com/fr/blog/le-aaa-cest-quoi)
    * [🚀 Elevate Your Unit Testing with the AAA Pattern in C#](https://www.linkedin.com/pulse/elevate-your-unit-testing-aaa-pattern-c-collins-tonui)
    * [PyCon UK 2016: Cleaner unit testing with the Arrange Act Assert pattern](https://www.youtube.com/watch?v=GGw5T1mw9vU)
* [Given When Then](https://martinfowler.com/bliki/GivenWhenThen.html)
* [AAA in PHPUnit](https://docs.phpunit.de/en/11.3/fixtures.html#fixtures)
* [Software testing methods](https://en.wikipedia.org/wiki/Software_testing)
* [Resources on TDD](https://xp123.com/resources-on-test-driven-development-tdd/)
* [Multiple Asserts Are OK](https://www.industriallogic.com/blog/multiple-asserts-are-ok/)
* [Four Pillars of a Good Test (Khorikov)](https://sammancoaching.org/learning_hours/test_design/four_pillars_khorikov.html)
* About Test Data Builders
  * [Refactoring test inputs with Test Data Builders](https://sammancoaching.org/learning_hours/test_design/test_data_builders.html)
  * [Test Data Builders](https://xtrem-tdd.netlify.app/Flavours/Testing/test-data-builders)
  * The library for Test Data Builder patter (used here): [Buildotter](https://github.com/php-core)
* About the outside-in diamond
  * [Outside-in Diamond 🔷 TDD #1 - a style made from (& for) ordinary people](https://tpierrain.blogspot.com/2021/03/outside-in-diamond-tdd-1-style-made.html)
  * [Outside-in Diamond 🔷 TDD #2 (anatomy of a style)](https://tpierrain.blogspot.com/2021/03/outside-in-diamond-tdd-2-anatomy-of.html)
  * [FR] [Outside-in Diamond pour écrire des tests Antifragiles & orientés métier - Thomas Pierrain](https://www.youtube.com/watch?v=09R8ROv3aKU)
