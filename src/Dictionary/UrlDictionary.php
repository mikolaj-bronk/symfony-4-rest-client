<?php

namespace App\Dictionary;

class UrlDictionary
{
    public const GET_ALL_ITEMS_URL = '/items';
    public const GET_ONE_ITEM_URL = '/item';
    public const GET_AVAILABLE_ITEMS_URL = '/items/found';
    public const GET_UNAVAILABLE_ITEMS_URL = '/items/notfound';
    public const GET_GREATER_THAN_FIVE_ITEMS_URL = '/items/foundfive';
    public const CREATE_ITEM_URL = '/add';
    public const DELETE_ITEM_URL = '/delete';
    public const UPDATE_ITEM_URL = '/update';
}