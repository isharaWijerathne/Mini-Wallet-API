<?php

namespace Http;

enum MiddlewareResponce: string {
    case NEXT = "GET";
    case FAIL = "FAIL";
    
}