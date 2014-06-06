<?php

// Member transformer
API::transform('App\Modules\Members\Models\Member', 'Api\Modules\Members\Transformers\MemberTransformer');
API::transform('App\Modules\Members\Models\MemberGroup', 'Api\Modules\Members\Transformers\MemberGroupTransformer');
API::transform('App\Modules\Members\Models\MemberGroupDetails', 'Api\Modules\Members\Transformers\MemberGroupDetailsTransformer');