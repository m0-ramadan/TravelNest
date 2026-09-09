<?php
namespace App\Models;
use App\Traits\HasTranslatableAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class NileCruiseItineraryActivity extends Model {
    use HasTranslatableAttributes;
    protected $fillable=['nile_cruise_itinerary_day_id','attraction_id','section_title','section_description','title','description','sort_order'];
    protected $casts=['section_title'=>'array','section_description'=>'array','title'=>'array','description'=>'array','sort_order'=>'integer'];
    public function day(): BelongsTo { return $this->belongsTo(NileCruiseItineraryDay::class,'nile_cruise_itinerary_day_id'); }
    public function attraction(): BelongsTo { return $this->belongsTo(Attraction::class); }
    public function getDisplayTitleAttribute(): string { return $this->translatedValue('title'); }
    public function getDisplayDescriptionAttribute(): string { return $this->translatedValue('description'); }
    public function getDisplaySectionTitleAttribute(): string { return $this->translatedValue('section_title'); }
    public function getDisplaySectionDescriptionAttribute(): string { return $this->translatedValue('section_description'); }
}
