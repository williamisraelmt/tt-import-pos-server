<?php


namespace App\Console\Commands\Models;


use App\DebtCollector;
use App\Invoice;
use App\Payment;
use Illuminate\Support\Collection;

class OdooPayment implements IOdooModel
{

    /* @var integer */
    private $id;
    /* @var string */
    private $name;
    /* @var int */
    private $amount;
    /* @var int */
    private $partnerId;
    /* @var Collection|OdooInvoice[] */
    private $paymentInvoices;
    /** @var string */
    private $paymentDate;
    /**
     * @var DebtCollector
     */
    private $debtCollector;

    /**
     * OdooPayment constructor.
     * @param int $id
     * @param string $name
     * @param int $amount
     * @param int $partnerId
     * @param Collection|OdooPaymentInvoice[] $paymentInvoices
     * @param string $paymentDate
     * @param DebtCollector|null $debtCollector
     */
    public function __construct(int $id, string $name, int $amount, int $partnerId, Collection $paymentInvoices, string $paymentDate, ?DebtCollector $debtCollector = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->amount = $amount;
        $this->partnerId = $partnerId;
        $this->paymentInvoices = $paymentInvoices;
        $this->paymentDate = $paymentDate;
        $this->debtCollector = $debtCollector;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getPartnerId(): int
    {
        return $this->partnerId;
    }

    /**
     * @param int $partnerId
     */
    public function setPartnerId(int $partnerId): void
    {
        $this->partnerId = $partnerId;
    }

    /**
     * @return Collection
     */
    public function getPaymentInvoices(): Collection
    {
        return $this->paymentInvoices;
    }

    /**
     * @param Collection $paymentInvoices
     */
    public function setPaymentInvoices(Collection $paymentInvoices): void
    {
        $this->paymentInvoices = $paymentInvoices;
    }

    /**
     * @return string
     */
    public function getPaymentDate(): string
    {
        return $this->paymentDate;
    }

    /**
     * @param string $paymentDate
     */
    public function setPaymentDate(string $paymentDate): void
    {
        $this->paymentDate = $paymentDate;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return DebtCollector
     */
    public function getDebtCollector(): ?DebtCollector
    {
        return $this->debtCollector;
    }

    /**
     * @param DebtCollector $debtCollector
     */
    public function setDebtCollector(DebtCollector $debtCollector): void
    {
        $this->debtCollector = $debtCollector;
    }

    public function toStoreAsArray(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "amount" => $this->amount,
            "payment_date" => $this->paymentDate,
            "debt_collector_id" => Payment::find($this->id)->debt_collector_id ?? $this->debtCollector->id ?? null,
            "customer_id" => $this->partnerId,
            "created_at" => Payment::find($this->id)->created_at ?? now()->toDateTimeString(),
            "updated_at" => now()->toDateTimeString()
        ];
    }
}
