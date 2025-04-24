<?php
namespace Http;

abstract class Controller {
    public abstract  function Execute();

    //For Make Controller Static
    public abstract  static function getInstance(): Controller;
}