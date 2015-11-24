<?php

namespace Netgen\BlockManager\Core\Service\Validator;

use Netgen\BlockManager\API\Service\Validator\LayoutValidator as LayoutValidatorInterface;
use Netgen\BlockManager\API\Values\LayoutCreateStruct as APILayoutCreateStruct;
use Netgen\BlockManager\Validator\Constraint\Layout;
use Netgen\BlockManager\Validator\Constraint\LayoutZones;
use Symfony\Component\Validator\Constraints;

class LayoutValidator extends Validator implements LayoutValidatorInterface
{
    /**
     * Validates layout create struct
     *
     * @param \Netgen\BlockManager\API\Values\LayoutCreateStruct $layoutCreateStruct
     *
     * @throws \Netgen\BlockManager\API\Exception\InvalidArgumentException If the validation failed
     */
    public function validateLayoutCreateStruct(APILayoutCreateStruct $layoutCreateStruct)
    {
        $this->validate(
            $layoutCreateStruct->name,
            array(
                new Constraints\NotBlank(),
                new Constraints\Type(array('type' => 'string')),
            ),
            'name'
        );

        $this->validate(
            $layoutCreateStruct->identifier,
            array(
                new Constraints\NotBlank(),
                new Constraints\Type(array('type' => 'string')),
                new Layout(),
            ),
            'identifier'
        );

        $this->validate(
            $layoutCreateStruct->zoneIdentifiers,
            array(
                new Constraints\NotBlank(),
                new Constraints\Type(array('type' => 'array')),
                new Constraints\All(
                    array(
                        'constraints' => array(
                            new Constraints\NotBlank(),
                            new Constraints\Type(array('type' => 'string')),
                        ),
                    )
                ),
                new LayoutZones(),
            ),
            'zoneIdentifiers'
        );
    }
}
