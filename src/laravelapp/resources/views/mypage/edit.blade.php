@extends('layouts.app')

@if(session('status'))
  <div 
  class="edit-profile-message"
  >
  {{ session('status')}}
  </div>
@endif