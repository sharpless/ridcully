<?php

function base_uri($uri)
{
  return CRidcully::Instance()->request->base_uri . trim($uri, '/');
}

function current_uri()
{
  return CRidcully::Instance()->request->current_uri;
}

function create_uri($uri)
{
  return CRidcully::Instance()->request->createUri($uri);
}