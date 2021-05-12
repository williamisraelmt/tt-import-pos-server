<?php


namespace App\Console\Commands\Models;


use App\Invoice;

class OdooInvoice implements IOdooModel
{
    /* @var integer */
    private $id;
    /* @var string */
    private $name;
    /* @var string */
    private $createDate;
    /* @var string */
    private $displayName;
    /* @var string */
    private $number;
    /* @var string */
    private $reference;
    /* @var string */
    private $dateInvoice;
    /* @var string */
    private $dateDue;
    /* @var string */
    private $partnerId;
    /* @var string */
    private $date;
    /* @var int */
    private $amountTotal;
    /* @var int */
    private $residual;

    /**
     * InvoiceModel constructor.
     * @param int $id
     * @param string|null $name
     * @param string|null $createDate
     * @param string|null $displayName
     * @param string|null $number
     * @param string|null $reference
     * @param string|null $dateInvoice
     * @param string|null $dateDue
     * @param string|null $partnerId
     * @param string|null $date
     * @param int|null $amountTotal
     * @param int|null $residual
     */
    public function __construct(
        int $id,
        string $name = null,
        string $createDate = null,
        string $displayName = null,
        string $number = null,
        string $reference = null,
        string $dateInvoice = null,
        string $dateDue = null,
        string $partnerId = null,
        string $date = null,
        int $amountTotal = null,
        int $residual = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->createDate = $createDate;
        $this->displayName = $displayName;
        $this->number = $number;
        $this->reference = $reference;
        $this->dateInvoice = $dateInvoice;
        $this->dateDue = $dateDue;
        $this->partnerId = $partnerId;
        $this->date = $date;
        $this->amountTotal = $amountTotal;
        $this->residual = $residual;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * @param mixed $createDate
     */
    public function setCreateDate($createDate): void
    {
        $this->createDate = $createDate;
    }

    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param mixed $displayName
     */
    public function setDisplayName($displayName): void
    {
        $this->displayName = $displayName;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number): void
    {
        $this->number = $number;
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     */
    public function setReference($reference): void
    {
        $this->reference = $reference;
    }

    /**
     * @return mixed
     */
    public function getDateInvoice()
    {
        return $this->dateInvoice;
    }

    /**
     * @param mixed $dateInvoice
     */
    public function setDateInvoice($dateInvoice): void
    {
        $this->dateInvoice = $dateInvoice;
    }

    /**
     * @return mixed
     */
    public function getDateDue()
    {
        return $this->dateDue;
    }

    /**
     * @param mixed $dateDue
     */
    public function setDateDue($dateDue): void
    {
        $this->dateDue = $dateDue;
    }

    /**
     * @return mixed
     */
    public function getPartnerId()
    {
        return $this->partnerId;
    }

    /**
     * @param mixed $partnerId
     */
    public function setPartnerId($partnerId): void
    {
        $this->partnerId = $partnerId;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getAmountTotal()
    {
        return $this->amountTotal;
    }

    /**
     * @param mixed $amountTotal
     */
    public function setAmountTotal($amountTotal): void
    {
        $this->amountTotal = $amountTotal;
    }

    /**
     * @return int
     */
    public function getResidual(): int
    {
        return $this->residual;
    }

    /**
     * @param int $residual
     */
    public function setResidual(int $residual): void
    {
        $this->residual = $residual;
    }

    public function toStoreAsArray(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "create_date" => $this->createDate,
            "display_name" => $this->displayName,
            "number" => $this->number,
            "reference" => $this->reference,
            "date_invoice" => $this->dateInvoice,
            "date_due" => $this->dateDue,
            "customer_id" => $this->partnerId,
            "date" => $this->date,
            "amount_total" => $this->amountTotal,
            "residual" => $this->residual,
            "created_at" => Invoice::find($this->id)->created_at ?? now()->toDateTimeString(),
            "updated_at" => now()->toDateTimeString()
        ];
    }

}
