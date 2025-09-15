<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use App\Custom\ValidationException;

trait RequestValidationTrait
{
    public function validateRequestDto(Request $request, string $dtoClass)
    {
        $data = $request->request->all();

        // Map fields from dto
        $dtoProps = array_map(fn($prop) => $prop->getName(), (new \ReflectionClass($dtoClass))->getProperties());

        // Sync with dto fields
        // Because of security meassures, this filter will exclude 
        // any input prop from request which is not defined in DTO class
        $filteredData = array_filter(
            $data,
            fn($key) => in_array($key, $dtoProps),
            ARRAY_FILTER_USE_KEY
        );

        // Get instance of new dto object and assign values from filteredData
        $dto = new $dtoClass();
        foreach ($filteredData as $key => $value) {
            $dto->$key = $value;
        }

        //Get instance of validator and enable attribute mapping
        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();

        // Once we get the instance of DTO with its annotation attributes, 
        // proceed to validation and formating error messages
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[$error->getPropertyPath()][] = $error->getMessage();
            }
            
            //Validation exception class is child class of BadRequest exception with added validations prop, 
            //So we can access it in Event listeners when error occurs
            $exception = new ValidationException('Validation failed');
            // Assigning validations to exception for complete response
            $exception->validations = $messages;
        
            throw $exception;
        }

        return $dto;
    }
}
