<?php

namespace Jet\Response\Http;

use Jet\Response\Http\Exceptions\InvalidResponse;

enum HttpStatus : string
{
    case OK = "200#ok#Ok.";
    case CREATED = "201#created#Request successful.";
    case ACCEPTED = "202#accepted#Request has been accepted.";
    case MOVED_PERMANENTLY = "301#movedPermanently#The resource URL is not available.";
    case MOVED = "301#moved#The resource URL is not available.";
    case FOUND = "302#found#The resource is not available at this time.";
    case BAD_REQUEST = "400#badRequest#Bad Request. Data not found.";
    case INVALID_TOKEN = "401#invalidToken#Unauthorized. Token missing or invalid key.";
    case UNAUTHORIZED = "401#unauthorized#Unauthorized. Requires authentication.";
    case PAYMENT_REQUIRED = "402#paymentRequired#Payment required.";
    case FORBIDDEN = "403#forbidden#Forbidden. Access not permitted.";
    case NOTFOUND = "404#notFound#Access or data not found.";
    case TIMEOUT = "408#timeout#Request Timeout. Too many requests failed to process.";
    case REQUEST_TIMEOUT = "408#requestTimeout#Request Timeout. Too many requests failed to process.";
    case CONFLICT = "409#conflict#Request not recognized.";
    case UNPROCESSABLE  = "422#unprocessable#Unprocessable Content.";
    case TOO_MANY_REQUEST = "429#tooManyRequest#Too Many Request. The request exceeds the specified limit.";
    case MANY_REQUEST = "429#manyRequest#Too Many Request. The request exceeds the specified limit.";
    case SERVER_ERROR = "500#serverError#Server Error. There was a problem with the internal server.";
    case ERROR = "500#error#Server Error. There was a problem with the internal server.";
    case BAD_GATEWAY = "502#badGateway#Bad Gateway. An error occurred on the server.";

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
     * Get Object By Keyword
     * 
     * @return \Jet\Response\Http\HttpStatus|null
     */
    public static function getObjectByKeyword(string|int|null $keyword): ?HttpStatus
    {
        $httpStatus = static::data();
        $result = array_keys(array_filter($httpStatus, function ($value) use ($keyword) {
            return stripos($value, "{$keyword}#") !== false;
        }));

        if(isset($result[0])) {
            $result = constant("\Jet\Response\Http\HttpStatus::{$result[0]}");
        }
        else{
            report(new InvalidResponse(...defineErrorResponse("{$keyword} is not available in HttpStatus enum.", 500)));
            $result = null;
        }

        return $result;
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