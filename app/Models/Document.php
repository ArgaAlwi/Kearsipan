<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_type_id', 
        'subject_id', 
        'number', 
        'title', 
        'year',
        'document_date', 
        'description', 
        'status', 
        'file_name', 
        'file_path', 
        'file_size', 
        'mime_type', 
        'uploaded_by'
    ];

    public function documentType() 
    { 
        return $this->belongsTo(DocumentType::class); 
    }
    
    public function subject() 
    { 
        return $this->belongsTo(Subject::class); 
    }
    
    public function uploader() 
    { 
        return $this->belongsTo(User::class, 'uploaded_by'); 
    }
    public function attachments() {
    return $this->hasMany(DocumentAttachment::class);
}
}