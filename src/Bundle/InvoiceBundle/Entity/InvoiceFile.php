<?php

declare(strict_types=1);

namespace Custom\Bundle\InvoiceBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Oro\Bundle\AccountBundle\Entity\Account;
use Oro\Bundle\AttachmentBundle\Entity\File;
use Oro\Bundle\ContactBundle\Entity\Contact;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * @ORM\Entity
 * @Config(
 *      routeName="invoice_file_index",
 *      routeView="invoice_file_view",
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
 *              "default"="invoice-files-grid"
 *          }
 *      }
 * )
 * @ORM\Table(name="invoice_files")
 */
class InvoiceFile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Custom\Bundle\InvoiceBundle\Entity\InvoiceSubCategory")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     *
     * @var InvoiceSubCategory
     */
    protected $category;

    /**
     * @var File
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\AttachmentBundle\Entity\File", cascade={"persist"})
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @JMS\Exclude
     */
    public $file;

    /**
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\AccountBundle\Entity\Account")
     * @ORM\JoinColumn(name="relatedAccount_id", referencedColumnName="id", onDelete="SET NULL")
     *
     * @var Account
     */
    protected $relatedAccount;

    /**
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\ContactBundle\Entity\Contact")
     * @ORM\JoinColumn(name="relatedContact_id", referencedColumnName="id", onDelete="SET NULL")
     *
     * @var Contact
     */
    public $relatedContact;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="oro.invoice.uploaded_at.label"
     *          }
     *      }
     * )
     */
    protected $uploadedAt;

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCategory(): ?InvoiceSubCategory
    {
        return $this->category;
    }

    public function setCategory(InvoiceSubCategory $category): void
    {
        $this->category = $category;
    }

    public function getRelatedAccount(): ?Account
    {
        return $this->relatedAccount;
    }

    public function setRelatedAccount(Account $relatedAccount): void
    {
        $this->relatedAccount = $relatedAccount;
    }

    public function getRelatedContact(): ?Contact
    {
        return $this->relatedContact;
    }

    public function setRelatedContact(Contact $contact): void
    {
        $this->relatedContact = $contact;
    }

    /**
     * @param DateTime|null $uploadedAt
     * @return InvoiceFile
     */
    public function setUploadedAt(DateTime $uploadedAt = null)
    {
        $this->uploadedAt = $uploadedAt;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUploadedAt()
    {
        return $this->uploadedAt;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File $file
     *
     * @return $this
     */
    public function setFile(File $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->uploadedAt = $this->uploadedAt ? $this->uploadedAt : new DateTime();
    }
}
