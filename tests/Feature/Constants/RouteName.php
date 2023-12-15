<?php

namespace Tests\Feature\Constants;

enum RouteName: string
{
    case LOGIN = 'login';
    case PRIVATE_TRAVELS_INDEX = 'api.admin.travels.index';
    case PRIVATE_TRAVELS_CREATE = 'api.admin.travels.create';
    case PRIVATE_TRAVELS_UPDATE = 'api.admin.travels.update';
    case PRIVATE_TRAVEL_TOURS_INDEX = 'api.admin.travel-tours.index';
    case PRIVATE_TRAVEL_TOURS_CREATE = 'api.admin.travel-tours.create';
    case PRIVATE_TRAVEL_TOURS_UPDATE = 'api.admin.travel-tours.update';
    case PUBLIC_TRAVELS_INDEX = 'api.public.travels.index';
    case PUBLIC_TRAVEL_TOURS_INDEX = 'api.public.travel-tours.index';
}
