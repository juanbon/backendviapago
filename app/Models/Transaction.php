<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $table = 'transactions';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'date', 'number', 'code', 'reference', 'ticket', 'type', 'status',
        'sourceUserId', 'sourceIdentityId', 'sourceAccountId', 'sourceName',
        'sourceCvu', 'sourceCbu', 'sourceAlias', 'sourceCuit', 'cardId',
        'targetUserId', 'targetIdentityId', 'targetAccountId', 'targetContactId',
        'targetName', 'targetCvu', 'targetCbu', 'targetAlias', 'targetCuit',
        'storeId', 'branchId', 'sailpointId', 'benefitId', 'amount', 'reason',
        'relatedTransactionId', 'bindId', 'reverseBindId', 'gireId', 'pomeloId',
        'reversePomeloId', 'companyCode', 'createdAt', 'updatedAt', 'deletedAt',
        'creditId', 'userId', 'coelsaId', 'reverseCoelsaId', 'reverseGireId',
        'onlineConciliation', 'offlineConciliation', 'micronautaId',
        'currentBalance'
    ];


    protected $visible = [
        // Campos de resumen
        'day', 'type', 'status', 'total', 'total_amount', 'avg_amount', 'date_block',
    
        // Campos alias usados para filtros
        'date', 'number', 'amount', 'type',
    
        // Campos importantes adicionales
        'sourceName', 'targetName', 'reason',
        'status', 'createdAt', 'targetCvu', 'sourceCvu', 'currentBalance'
    ];


    protected $dates = ['date', 'createdAt', 'updatedAt', 'deletedAt'];

    // Relaciones
    public function sourceUser()
    {
        return $this->belongsTo(User::class, 'sourceUserId');
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'targetUserId');
    }

    public function relatedTransaction()
    {
        return $this->belongsTo(Transaction::class, 'relatedTransactionId');
    }

    public function credit()
    {
        return $this->belongsTo(Credit::class, 'creditId');
    }

    public function card()
    {
        return $this->belongsTo(Card::class, 'cardId');
    }

    public static $filterAliases = [
        'FechaDesde'      => 'date',
        'FechaHasta'      => 'date',
        'NroTransaccion'  => 'number',
        'Importe'         => 'amount',
        'TypeTransaction' => 'type',
    ];

    // etc., otras relaciones opcionales como branch, store, etc.
}
