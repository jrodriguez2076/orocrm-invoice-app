<?php

declare(strict_types=1);

namespace Custom\Bundle\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

/**
 * @ORM\Entity
 * @Config(
 *      routeName="invoice_category_index",
 *      routeView="invoice_category_view    ",
 *      defaultValues={
 *          "dataaudit"={
 *              "auditable"=false
 *          },
 *          "entity"={
 *              "icon"="fa-book"
 *          },
 *          "security"={
 *              "type"="ACL",
 *              "category"="account_management"
 *          },
 *          "grid"={
 *              "default"="invoice-category-grid"
 *          }
 *      }
 * )
 * @ORM\Table(name="invoice_categories")
 */
class InvoiceCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $name;

    /**
     * @ORM\Column(type="boolean")
     */
    public $invoiceFormEnabled = false;

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function isInvoiceFormEnabled(): bool
    {
        return (bool) $this->invoiceFormEnabled;
    }

    public function setInvoiceFormEnabled(?bool $invoiceFormEnabled): void
    {
        $this->invoiceFormEnabled = $invoiceFormEnabled;
    }
}
