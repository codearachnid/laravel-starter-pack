Use the [Prunable trait](https://laravel.com/docs/11.x/eloquent#pruning-models) in Laravel to automatically remove outdated records. Define a prunable method in your model 
to specify the condition, then schedule model:prune to run daily. For faster deletion without model events, use 
MassPrunable. This skips fetching records before deletion, making pruning more efficient.

```php
<?php
namespace App Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate \Database\Eloquent\Prunable;

class User extends Model
{
  use Prunable;

  /**
   * Define pruning condition: Remove users who haven't verified their email after 6 months.
   */
  public function prunable(): Builder
  {
    return static::whereNull( 'email_verified_at')->where( 'created_at', '<', now()→subMonths (6));
  }
｝
```

Then in routes/console.php
```php
<?php

use Illuminate\Support\Facades\Schedule;

Schedule:: command ('model: prune')->daily();

```
