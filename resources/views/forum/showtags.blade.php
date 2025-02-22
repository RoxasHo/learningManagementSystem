<x-layout>
  <link rel="stylesheet" href="{{ asset('css/show_tags.css') }}">

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<div class="main-content" id="main-content">
<h3>Hot Topics</h3>

<!-- Search bar for filtering tags -->
<div class="search-container">
    <form method="GET" action="{{ route('tags.search') }}">
        <input type="text" name="search" placeholder="Search topics..." value="{{ request('search') }}" class="search-input">
        <button type="submit" class="search-button">Search</button>
    </form>
    <a href="{{ route('show.main') }}">
    <button class="back-to-home-btn">
    <span class="material-symbols-outlined">arrow_back</span>
    Back
    </button>
    </a>
</div>

<div class="divider"></div>

<div class="tags-container">     
    <div class="tags-list">
        @if(request('search'))
            @if($searchedTags->isNotEmpty())
                @foreach($searchedTags as $tag)
                    <div class="tag">
                        <a href="{{ route('posts.byTag', ['tag' => $tag]) }}">#{{ $tag }}</a>

                        @if(auth()->check())
                    @php
                        $isFollowing = auth()->user()->followedTags()->where('tag', $tag)->exists();
                    @endphp
                    @if($isFollowing)
                        <form action="{{ route('tags.unfollow') }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="tag" value="{{ $tag }}">
                            <button type="submit" class="follow-button" style="background: none; border: none; cursor: pointer;">
                            <span class="material-symbols-outlined">
                            remove
                            </span>
                        </form>
                    @else
                        <form action="{{ route('tags.follow') }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="tag" value="{{ $tag }}">
                            <button type="submit" class="follow-button" style="background: none; border: none; cursor: pointer;">
        <span class="material-symbols-outlined">add</span>
                        </form>
                    @endif
                @endif

                    </div>
                @endforeach
            @else
                <p>No matching tags found.</p>
            @endif
        @else


                @foreach($tagsArray as $tag)
            <div class="tag">
                <a href="{{ route('posts.byTag', ['tag' => $tag]) }}">#{{ $tag }}</a>

                @if(auth()->check())
                    @php
                        $isFollowing = auth()->user()->followedTags()->where('tag', $tag)->exists();
                    @endphp
                    @if($isFollowing)
                        <form action="{{ route('tags.unfollow') }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="tag" value="{{ $tag }}">
                            <button type="submit" class="follow-button" style="background: none; border: none; cursor: pointer;">
                            <span class="material-symbols-outlined">
                            remove
                            </span>
                        </form>
                    @else
                        <form action="{{ route('tags.follow') }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="tag" value="{{ $tag }}">
                            <button type="submit" class="follow-button" style="background: none; border: none; cursor: pointer;">
        <span class="material-symbols-outlined">add</span>
                        </form>
                    @endif
                @endif
            </div>
        @endforeach
            

        @endif
    </div>
</div>

</div>
</x-layout>