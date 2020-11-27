<?php

declare(strict_types=1);

namespace Custom\Bundle\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

/**
 * @ORM\Entity
 * @Config(
 *      routeName="invoice_subcategory_index",
 *      routeView="invoice_subcategory_view    ",
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
 *              "default"="invoice-subcategory-grid"
 *          }
 *      }
 * )
 * @ORM\Table(name="invoice_subcategories")
 */
class InvoiceSubCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="InvoiceCategory")
     * @ORM\JoinColumn(name="category_id", onDelete="SET NULL")
     *
     * @var InvoiceCategory
     */
    protected $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $name;

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCategory(): ?InvoiceCategory
    {
        return $this->category;
    }

    public function setCategory(InvoiceCategory $category): void
    {
        $this->category = $category;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
