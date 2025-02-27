<?php

namespace JetResponse\Http;

enum HttpStatus : string
{
    case OK = "200#ok#Ok.";
    case CREATED = "201#created#Request successful.";
    case ACCEPTED = "202#accepted#Request has been accepted.";
    case MOVEDPERMANENTLY = "301#movedPermanently#The resource URL is not available.";
    case MOVED = "301#moved#The resource URL is not available.";
    case FOUND = "302#found#The resource is not available at this time.";
    case BADREQUEST = "400#badRequest#Access not found.";
    case INVALIDTOKEN = "401#invalidToken#Token missing or invalid key.";
    case UNAUTHORIZED = "401#unauthorized#Requires authentication.";
    case PAYMENTREQUIRED = "402#paymentRequired#Payment required.";
    case FORBIDDEN = "403#forbidden#Invalid access.";
    case NOTFOUND = "404#notFound#Access or data not found.";
    case TIMEOUT = "408#timeout#Too many requests failed to process.";
    case REQUESTTIMEOUT = "408#requestTimeout#Too many requests failed to process.";
    case CONFLICT = "409#conflict#Request not recognized.";
    case TOOMANYREQUEST = "429#tooManyRequest#The request exceeds the specified limit.";
    case MANYREQUEST = "429#manyRequest#The request exceeds the specified limit.";
    case SERVERERROR = "500#serverError#There was a problem with the internal server.";
    case ERROR = "500#error#There was a problem with the internal server.";
    case BADGATEWAY = "502#badGateway#An error occurred on the server.";

    /**
     * Data to Array
     * 
     * @return array
     */
    public static function data(): array
    {
        return array_combine(
            array_column(self::cases(), 'name'),
            array_column(self::cases(), 'value'),
        );
    }

    /**
     * CONVERTION DATA
     * string (enum value) to Array
     * 
     * @return array
     */
    private function explode(HttpStatus $case): array
    {
        return explode('#', $case->value);
    }

    /**
     * GET Status Code
     * 
     * @return int
     */
    public function code(): int
    {
        return $this->explode($this)[0] ?? 0;
    }

    /**
     * GET Status Name
     * Initial Name as Unique Code
     * 
     * @return string
     */
    public function name(): string
    {
        return $this->explode($this)[1] ?? '';
    }

    /**
     * GET Status Description (Message)
     * 
     * @return string
     */
    public function message(): string
    {
        return $this->explode($this)[2] ?? '';
    }
}