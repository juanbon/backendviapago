<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions'; // Opcional si el nombre coincide

    protected $fillable = [
        'questionCategoryId',
        'question',
        'answer',
        'status',
    ];

    public static $filterAliases = [
      'Pregunta' => 'question',
    //  'Estado' => 'status',
  ];

    public $timestamps = false; // o true si usás timestamps automáticos

    // Relaciones (opcional, pero recomendable)
    public function questionCategory()
    {
      //   return $this->belongsTo(QuestionCategory::class, 'questionCategoryId');
    }
}
