@if($posts->count() > 0)

    <div class = "space-y-4">

        @foreach($posts as $post)

            <div class = "card dashboard-card mb-4 post-card-hover" onclick = "window.location.href = '{{ route('posts.show', $post) }}'" style = "cursor: pointer;">

                <div class = "card-body">

                    <div class = "d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom border-light border-opacity-25">

                        <div class = "d-flex align-items-center">

                            <img src = "{{ $post->user->profile_photo_url }}" alt = "{{ $post->user->username }}" class = "rounded-circle me-3"  style = "width: 48px; height: 48px; object-fit: cover;">

                            <div>

                                <h6 class = "text-white fw-semibold mb-1">{{ $post->user->username }}</h6>
                                <small class = "text-white-50">{{ $post->created_at->diffForHumans() }}</small>

                            </div>

                        </div>

                        <span class = "badge post-category-badge">{{ ucfirst($post->category->type) }}</span>

                    </div>

                    @if($post->title)

                        <h5 class = "text-white fw-bold mb-3">{{ $post->title }}</h5>

                    @endif

                    @if($post->description)

                        <div class = "text-white-75 mb-3">

                            {{ Str::limit($post->description, 200) }}

                        </div>

                    @endif

                    @if($post->spotify_data)

                        <div class = "spotify-preview-card mb-3">

                            <div class = "d-flex align-items-center gap-3">

                                @if($post->spotify_image)

                                    <img src = "{{ $post->spotify_image }}" alt = "{{ $post->spotify_name }}" class = "rounded-3 flex-shrink-0" style = "width: 60px; height: 60px; object-fit: cover;">

                                @endif

                                <div class = "flex-grow-1 min-w-0">

                                    <div class = "text-white fw-medium">

                                        {{ $post->spotify_name }}

                                    </div>

                                    @if($post->spotify_artist)

                                        <div class = "text-white-50 small">{{ $post->spotify_artist }}</div>

                                    @endif

                                    <div class = "d-flex align-items-center mt-1">

                                        <i class = "fab fa-spotify text-success me-1"></i>

                                        <span class = "text-white-50 small">Spotify</span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endif

                    <div class = "d-flex justify-content-between align-items-center pt-3 border-top border-light border-opacity-25">

                        <div class = "d-flex gap-4">

                            <span class = "text-light small d-flex align-items-center">

                                {{ $post->likes_count ?? 0 }} likes

                            </span>

                            <span class = "text-light small d-flex align-items-center">

                                {{ $post->comments_count ?? 0 }} comentarios

                            </span>

                        </div>

                        <div class = "text-light small">

                            Ver completo

                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    </div> 

    @if($posts->hasPages())

        <div class = "d-flex justify-content-center mt-4">

            {{ $posts->appends(request()->except('posts_page'))->links('pagination::bootstrap-4', ['class'  => 'pagination-custom']) }}

        </div>

    @endif

@else

    <div class = "card dashboard-card">

        <div class = "card-body text-center py-5">

            <i class = "fas fa-newspaper fa-3x text-light mb-3"></i>

            <h5 class = "text-white mb-2">Sin publicaciones</h5>

            <p class = "text-light">{{ $user->name ?? 'Este usuario' }} aún no ha realizado ninguna publicación.</p>

        </div>

    </div>

@endif