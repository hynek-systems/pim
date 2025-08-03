<?php

namespace Hynek\Pim\Forms\Controls;

use App\Form\Controls\TextInput;

class PriceInput extends TextInput
{
    protected string $type = 'text';

    protected ?string $suffix = '$';

    protected string $decimal_places = '2';

    protected string $thousand_separator = ',';

    protected string $decimal_separator = '.';

    public function getDecimalPlaces(): string
    {
        return $this->decimal_places;
    }

    public function setDecimalPlaces(string $decimal_places): static
    {
        $this->decimal_places = $decimal_places;

        return $this;
    }

    public function getThousandSeparator(): string
    {
        return $this->thousand_separator;
    }

    public function setThousandSeparator(string $thousand_separator): static
    {
        $this->thousand_separator = $thousand_separator;

        return $this;
    }

    public function getDecimalSeparator(): string
    {
        return $this->decimal_separator;
    }

    public function setDecimalSeparator(string $decimal_separator): static
    {
        $this->decimal_separator = $decimal_separator;

        return $this;
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        $array[1] = array_merge(
            $array[1] ?? [],
            [
                'decimal_places' => $this->decimal_places,
                'thousand_separator' => $this->thousand_separator,
                'decimal_separator' => $this->decimal_separator,
            ]
        );

        return $array;
    }
}
