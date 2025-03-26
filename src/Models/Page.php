<?php

namespace Danaei\ShopCMS\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    //
    protected $fillable = [
        'slug',            
        'title',           
        'description',     
        'keywords',        
        'robots_index',    
        'og_title',        
        'og_description',  
        'og_image',        
        'twitter_card',    
        'canonical_url',   
        'hreflang',        
        'schema_markup',   
    ];
}
