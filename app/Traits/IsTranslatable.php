<?php
/**
 * Created by Abanoub Nassem
 * Date: 2018-12-26
 * Time: 13:05
 */


namespace App\Traits;

use Spatie\Translatable\HasTranslations;

trait IsTranslatable
{
    use HasTranslations;

    protected  $withTranslations = true;

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $toArray = parent::toArray();
        if ($this->withTranslations) {
            foreach ($this->getTranslatableAttributes() as $attr) {
                $toArray[$attr] = $this->getTranslation($attr, $this->getLocale());
            }
        }
        return $toArray;
    }

    public function withOutTranslations()
    {
        $this->withTranslations = false;
        return $this;
    }

}
