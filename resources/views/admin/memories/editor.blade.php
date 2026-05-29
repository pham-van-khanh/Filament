<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Editor - {{ $post->title }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#efede7] font-sans text-neutral-900">
  <form method="POST" action="{{ route('admin.memories.editor.update', $post) }}" id="editorForm" class="min-h-screen">
    @csrf
    @method('PUT')

    <div id="sectionInputs"></div>

    <header class="sticky top-0 z-40 flex h-[68px] items-center gap-3 border-b border-black/10 bg-white/95 px-4 backdrop-blur-xl">
      <a href="{{ route('home') }}" class="text-xl font-bold text-[#812744]">chuaminh.vn</a>
      <span class="hidden h-8 w-px bg-black/10 md:block"></span>
      <span class="hidden min-w-0 truncate rounded-xl bg-neutral-100 px-3 py-2 text-xs font-semibold text-neutral-600 lg:block">memories/{{ $post->slug }}</span>
      <button type="button" id="mobilePreview" class="editor-pill bg-white">Mobile</button>
      <button type="button" id="desktopPreview" class="editor-pill bg-white">Desktop</button>
      <span class="ml-auto hidden text-sm font-semibold text-emerald-700 md:inline-flex">Da luu cuc bo</span>
      <a href="{{ route('memories.show', $post->slug) }}" target="_blank" class="editor-pill bg-white">Xem truoc</a>
      <button type="submit" data-submit-status="draft" class="editor-pill bg-white">Luu nhap</button>
      <button type="submit" data-submit-status="published" class="editor-pill border-[#812744] bg-[#812744] text-white">Dang bai</button>
    </header>

    <div class="editor-workspace">
      <aside class="editor-sidebar border-r">
        <div class="flex border-b border-black/10 text-sm font-semibold">
          <button type="button" data-editor-tab="templates" class="editor-tab active">Template</button>
          <button type="button" data-editor-tab="blocks" class="editor-tab">Blocks</button>
          <button type="button" data-editor-tab="media" class="editor-tab">Anh</button>
        </div>

        <div data-tab-panel="templates" class="space-y-6 p-4">
          @foreach($templates->groupBy('category') as $group => $items)
            <section>
              <h2 class="mb-3 text-xs font-bold uppercase tracking-[0.18em] text-neutral-500">{{ $group }}</h2>
              <div class="grid grid-cols-2 gap-2.5">
                @foreach($items as $template)
                  @php
                    $primary = data_get($template->design_tokens, 'colors.primary', data_get($template->design_tokens, 'colors.accent', '#d84d80'));
                    $surface = data_get($template->design_tokens, 'colors.background', '#f7f7f4');
                  @endphp
                  <label class="editor-template-card {{ $post->template_id === $template->id ? 'active' : '' }}">
                    <input type="radio" name="template_id" value="{{ $template->id }}" class="sr-only" @checked($post->template_id === $template->id)>
                    <span class="block h-14 rounded-t-lg" style="background: linear-gradient(135deg, {{ $primary }}, {{ $surface }});"></span>
                    <span class="block truncate px-2 py-2 text-xs font-bold">{{ $template->name }}</span>
                  </label>
                @endforeach
              </div>
            </section>
          @endforeach
        </div>

        <div data-tab-panel="blocks" class="hidden p-4">
          @foreach($addableSectionTypes->groupBy('category') as $group => $items)
            <section class="mb-6">
              <h2 class="mb-3 text-xs font-bold uppercase tracking-[0.18em] text-neutral-500">{{ $group }}</h2>
              <div class="grid grid-cols-2 gap-2.5">
                @foreach($items as $type)
                  <button type="button" class="editor-add-block" data-add-block="{{ $type->slug }}">
                    <span class="block text-[11px] font-bold uppercase text-[#812744]">{{ str($type->slug)->replace('_', ' ')->limit(10) }}</span>
                    <span class="mt-1 block text-sm font-bold">{{ $type->name }}</span>
                  </button>
                @endforeach
              </div>
            </section>
          @endforeach
        </div>

        <div data-tab-panel="media" class="hidden p-4">
          <label for="mediaUploadInput" class="mb-3 block cursor-pointer rounded-xl border border-dashed border-[#e34d86] bg-[#fff7fb] px-4 py-4 text-center text-sm font-bold text-[#812744]">Upload anh/video/nhac</label>
          <input id="mediaUploadInput" type="file" accept="image/*,video/*,audio/*,application/pdf" multiple class="sr-only">
          <p id="mediaUploadStatus" class="mb-4 min-h-5 text-xs font-semibold text-neutral-500"></p>
          <div id="mediaLibrary" class="grid grid-cols-3 gap-2">
            @foreach($media as $item)
              <button type="button" class="aspect-square overflow-hidden rounded-xl bg-neutral-100 ring-1 ring-black/5" data-media-id="{{ $item->id }}" title="{{ $item->original_name }}">
                @if($item->display_url && $item->type === \App\Enums\MediaType::Image)
                  <img src="{{ $item->display_url }}" alt="{{ $item->alt }}" class="h-full w-full object-cover">
                @else
                  <span class="grid h-full place-items-center p-2 text-center text-[11px] font-semibold text-neutral-500">{{ $item->original_name }}</span>
                @endif
              </button>
            @endforeach
          </div>
        </div>
      </aside>

      <main class="overflow-auto p-4 md:p-7">
        <div id="canvasShell" class="mx-auto w-[390px] max-w-full transition-all">
          <div class="overflow-hidden rounded-[22px] border border-black/15 bg-white shadow-2xl">
            <div class="flex h-8 items-center justify-between bg-white px-4 text-xs font-bold text-neutral-500">
              <span>9:41</span>
              <span>chuaminh.vn</span>
            </div>
            <div id="canvas" class="min-h-[620px] bg-white">
              <div id="canvasBlocks"></div>
              <button type="button" id="addDefaultBlock" class="m-4 w-[calc(100%-32px)] rounded-xl border border-dashed border-neutral-400 py-4 text-sm font-bold text-neutral-500">Them block</button>
            </div>
          </div>
        </div>
      </main>

      <aside class="editor-sidebar border-l">
        <div class="border-b border-black/10 p-4">
          <p class="truncate text-xs font-bold uppercase tracking-[0.16em] text-blue-700">/memories/{{ $post->slug }}</p>
        </div>

        <div class="space-y-4 p-4">
          <section class="editor-soft-panel">
            <div class="mb-3 flex items-center justify-between">
              <h2 class="text-sm font-extrabold uppercase tracking-[0.14em] text-neutral-600">Bai viet</h2>
              <span class="rounded-full bg-neutral-100 px-2 py-1 text-[11px] font-bold text-neutral-500">{{ $post->status->value }}</span>
            </div>
            <label class="editor-field">
              <span>Tieu de</span>
              <input name="title" value="{{ old('title', $post->title) }}" required>
            </label>
            <div class="mt-3 grid grid-cols-2 gap-3">
              <label class="editor-field">
                <span>Slug</span>
                <input name="slug" value="{{ old('slug', $post->slug) }}" required>
              </label>
              <label class="editor-field">
                <span>Ngay</span>
                <input name="memory_date" type="date" value="{{ optional($post->memory_date)->format('Y-m-d') }}">
              </label>
            </div>
            <label class="editor-field mt-3">
              <span>Khoang ngay hien thi</span>
              <input name="date_range" value="{{ old('date_range', $post->date_range) }}" placeholder="22 - 25 thang 9, 2025">
            </label>
            <label class="editor-field mt-3">
              <span>Dia diem</span>
              <input name="location_name" value="{{ old('location_name', $post->location_name) }}">
            </label>
            <label class="editor-field mt-3">
              <span>Tom tat</span>
              <textarea name="excerpt" rows="2">{{ old('excerpt', $post->excerpt) }}</textarea>
            </label>
            <div class="mt-3 grid grid-cols-2 gap-3">
              <label class="editor-field">
                <span>Danh muc</span>
                <select name="category_id">
                  <option value="">Khong chon</option>
                  @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected($post->category_id === $category->id)>{{ $category->name }}</option>
                  @endforeach
                </select>
              </label>
              <label class="editor-field">
                <span>Anh bia</span>
                <select name="cover_media_id" id="coverMediaSelect">
                  <option value="">Khong chon</option>
                  @foreach($media as $item)
                    <option value="{{ $item->id }}" @selected($post->cover_media_id === $item->id)>{{ $item->original_name }}</option>
                  @endforeach
                </select>
              </label>
            </div>
            <div class="mt-3 grid grid-cols-2 gap-3">
              <label class="editor-field">
                <span>Trang thai</span>
                <select name="status" id="statusSelect">
                  <option value="draft" @selected($post->status->value === 'draft')>Ban nhap</option>
                  <option value="published" @selected($post->status->value === 'published')>Cong khai</option>
                  <option value="hidden" @selected($post->status->value === 'hidden')>An</option>
                </select>
              </label>
              <label class="editor-field">
                <span>Hien thi</span>
                <select name="visibility">
                  <option value="public" @selected($post->visibility->value === 'public')>Public</option>
                  <option value="private" @selected($post->visibility->value === 'private')>Private</option>
                  <option value="unlisted" @selected($post->visibility->value === 'unlisted')>Unlisted</option>
                  <option value="password" @selected($post->visibility->value === 'password')>Password</option>
                </select>
              </label>
            </div>
          </section>

          <section class="editor-soft-panel">
            <div class="mb-3 flex items-center justify-between">
              <h2 class="text-sm font-extrabold uppercase tracking-[0.14em] text-neutral-600">Nhac nen</h2>
              <label class="inline-flex items-center gap-2 text-xs font-bold text-neutral-600">
                <input type="checkbox" name="music_enabled" value="1" @checked(old('music_enabled', $post->music_enabled))>
                Bat
              </label>
            </div>
            <label class="editor-field"><span>Link nhac</span><input name="music_url" value="{{ old('music_url', $post->music_url) }}" placeholder="Spotify, SoundCloud, MP3..."></label>
            <div class="mt-3 grid grid-cols-2 gap-3">
              <label class="editor-field"><span>Ten bai</span><input name="music_title" value="{{ old('music_title', $post->music_title) }}"></label>
              <label class="editor-field"><span>Nghe si</span><input name="music_artist" value="{{ old('music_artist', $post->music_artist) }}"></label>
            </div>
          </section>

          <section class="editor-soft-panel">
            <div class="mb-3 flex items-center justify-between">
              <h2 class="text-sm font-extrabold uppercase tracking-[0.14em] text-neutral-600">Block dang chon</h2>
              <button type="button" id="duplicateBlock" class="text-xs font-bold text-[#e34d86]">Nhan doi</button>
            </div>

            <div id="emptyBlockPanel" class="rounded-xl border border-dashed border-black/15 p-5 text-center text-sm font-semibold text-neutral-500">
              Chon mot block tren preview de chinh.
            </div>

            <div id="blockEditorPanel" class="hidden space-y-3">
              <div class="grid grid-cols-2 gap-3">
                <label class="editor-field">
                  <span>Loai block</span>
                  <select data-bind="type" id="selectedBlockType">
                    @foreach($sectionTypes as $type)
                      <option value="{{ $type->slug }}">{{ $type->name }}</option>
                    @endforeach
                  </select>
                </label>
                <label class="editor-field">
                  <span>Kieu hien thi</span>
                  <input data-bind="variant" placeholder="mosaic, prose...">
                </label>
              </div>
              <label class="editor-field">
                <span>Ten block/layer</span>
                <input data-bind="title">
              </label>
              <label class="flex items-center gap-2 text-sm font-bold text-neutral-700">
                <input type="checkbox" data-bind="is_visible">
                Hien thi block nay
              </label>

              <div data-block-panel="hero_image" class="editor-panel">
                <label class="editor-field"><span>Tieu de hero</span><input data-bind="headline"></label>
                <label class="editor-field"><span>Anh hero</span><select data-bind="media_id" data-media-select data-media-type="image"></select></label>
                <label class="editor-field"><span>Chieu cao</span><select data-bind="height"><option value="">Mac dinh</option><option value="360px">Nho</option><option value="520px">Vua</option><option value="680px">Lon</option></select></label>
                <label class="editor-field"><span>Chu thich phu</span><textarea data-bind="caption" rows="2"></textarea></label>
              </div>

              <div data-block-panel="stats" class="editor-panel">
                <div class="flex items-center justify-between">
                  <span class="text-xs font-bold uppercase tracking-[0.14em] text-neutral-500">Cac o thong ke</span>
                  <button type="button" data-add-item="stats" class="text-xs font-bold text-[#e34d86]">Them stat</button>
                </div>
                <div data-items-editor="stats" class="space-y-2"></div>
              </div>

              <div data-block-panel="rich_text" class="editor-panel">
                <label class="editor-field"><span>Noi dung</span><textarea data-bind="body" rows="8" placeholder="Viet mot doan ve ky niem nay..."></textarea></label>
              </div>

              <div data-block-panel="quote" class="editor-panel">
                <label class="editor-field"><span>Noi dung trich dan</span><textarea data-bind="quote_text" rows="5"></textarea></label>
                <label class="editor-field"><span>Nguoi viet/nguon</span><input data-bind="quote_author"></label>
              </div>

              <div data-block-panel="single_image" class="editor-panel">
                <label class="editor-field"><span>Anh</span><select data-bind="media_id" data-media-select data-media-type="image"></select></label>
                <label class="editor-field"><span>Caption</span><textarea data-bind="caption" rows="3"></textarea></label>
                <label class="editor-field"><span>Chieu cao</span><select data-bind="height"><option value="">Tu dong</option><option value="280px">280px</option><option value="420px">420px</option><option value="620px">620px</option></select></label>
              </div>

              <div data-block-panel="image_text" class="editor-panel">
                <label class="editor-field"><span>Anh</span><select data-bind="media_id" data-media-select data-media-type="image"></select></label>
                <label class="editor-field"><span>Noi dung</span><textarea data-bind="body" rows="6"></textarea></label>
                <label class="editor-field"><span>Layout</span><select data-bind="layout"><option value="">Anh trai</option><option value="text_left">Chu trai</option><option value="stacked">Xep doc</option></select></label>
              </div>

              <div data-block-panel="gallery_grid" class="editor-panel">
                <label class="editor-field"><span>Kieu bo cuc</span><select data-bind="layout"><option value="">Mosaic 6 anh</option><option value="grid_2">Grid 2 cot</option><option value="grid_3">Grid 3 cot</option><option value="featured_left">Anh lon ben trai</option><option value="masonry">Masonry</option><option value="film_strip">Film strip ngang</option><option value="polaroid">Polaroid</option></select></label>
                <div class="flex items-center justify-between">
                  <span class="text-xs font-bold uppercase tracking-[0.14em] text-neutral-500">Anh trong gallery</span>
                  <button type="button" data-add-item="gallery_grid" class="text-xs font-bold text-[#e34d86]">Them anh</button>
                </div>
                <div data-items-editor="gallery_grid" class="space-y-2"></div>
              </div>

              <div data-block-panel="gallery_slider" class="editor-panel">
                <div class="grid grid-cols-2 gap-3">
                  <label class="editor-field"><span>Chieu cao slide</span><select data-bind="height"><option value="">Mac dinh</option><option value="220px">220px</option><option value="300px">300px</option><option value="420px">420px</option></select></label>
                  <label class="flex items-end gap-2 pb-3 text-sm font-bold text-neutral-700"><input type="checkbox" data-bind="autoplay"> Tu dong chay</label>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-xs font-bold uppercase tracking-[0.14em] text-neutral-500">Slide anh</span>
                  <button type="button" data-add-item="gallery_slider" class="text-xs font-bold text-[#e34d86]">Them slide</button>
                </div>
                <div data-items-editor="gallery_slider" class="space-y-2"></div>
              </div>

              <div data-block-panel="video_embed" class="editor-panel">
                <label class="editor-field"><span>Video da upload</span><select data-bind="media_id" data-media-select data-media-type="video"></select></label>
                <label for="videoUploadInput" class="block cursor-pointer rounded-xl border border-dashed border-[#e34d86] bg-[#fff7fb] px-4 py-3 text-center text-sm font-bold text-[#812744]">Upload video moi</label>
                <input id="videoUploadInput" type="file" accept="video/*" class="sr-only">
                <label class="editor-field"><span>Caption</span><textarea data-bind="caption" rows="3"></textarea></label>
                <label class="editor-field"><span>Ty le khung hinh</span><select data-bind="layout"><option value="">16:9 ngang</option><option value="square">1:1 vuong</option><option value="vertical">9:16 doc</option></select></label>
              </div>

              <div data-block-panel="music" class="editor-panel">
                <label class="editor-field"><span>Link nhac</span><input data-bind="url" placeholder="Spotify, SoundCloud, MP3..."></label>
                <div class="grid grid-cols-2 gap-3">
                  <label class="editor-field"><span>Ten bai</span><input data-bind="headline"></label>
                  <label class="editor-field"><span>Nghe si</span><input data-bind="subtitle"></label>
                </div>
                <label class="flex items-center gap-2 text-sm font-bold text-neutral-700"><input type="checkbox" data-bind="autoplay"> Tu dong phat khi vao trang</label>
              </div>

              <div data-block-panel="timeline" class="editor-panel">
                <div class="flex items-center justify-between">
                  <span class="text-xs font-bold uppercase tracking-[0.14em] text-neutral-500">Cac moc ky niem</span>
                  <button type="button" data-add-item="timeline" class="text-xs font-bold text-[#e34d86]">Them moc</button>
                </div>
                <div data-items-editor="timeline" class="space-y-2"></div>
              </div>

              <div data-block-panel="ending" class="editor-panel">
                <label class="editor-field"><span>Tieu de ket</span><input data-bind="headline"></label>
                <label class="editor-field"><span>Noi dung ket</span><textarea data-bind="body" rows="6"></textarea></label>
              </div>

              <div data-block-panel="default" class="editor-panel">
                <label class="editor-field"><span>Anh</span><select data-bind="media_id" data-media-select data-media-type="image"></select></label>
                <label class="editor-field"><span>Noi dung</span><textarea data-bind="body" rows="4"></textarea></label>
                <label class="editor-field"><span>Caption/link</span><input data-bind="caption"></label>
              </div>

              <div class="flex gap-2 pt-2">
                <button type="button" id="deleteBlock" class="rounded-xl border border-red-200 px-3 py-2 text-xs font-bold text-red-600">Xoa block</button>
              </div>
            </div>
          </section>

          <section class="editor-soft-panel">
            <h2 class="mb-3 text-sm font-extrabold uppercase tracking-[0.14em] text-neutral-600">Layers</h2>
            <div id="layerList" class="space-y-2"></div>
          </section>
        </div>
      </aside>
    </div>
  </form>

  @php
    $editorMedia = $media->map(fn ($item) => [
      'id' => $item->id,
      'url' => $item->display_url,
      'name' => $item->original_name,
      'type' => $item->type instanceof \BackedEnum ? $item->type->value : $item->type,
    ])->values();

    $editorSections = $post->sections->map(function ($section) {
      $items = $section->items->map(fn ($item) => [
        'media_id' => $item->media_id,
        'title' => $item->title,
        'subtitle' => $item->subtitle,
        'value' => $item->value,
        'label' => $item->label,
        'time_label' => $item->time_label,
        'body' => $item->body,
        'caption' => $item->caption,
        'url' => $item->url,
      ])->values()->all();

      return [
        'type' => $section->type,
        'title' => $section->title,
        'subtitle' => $section->subtitle,
        'variant' => $section->variant,
        'media_id' => $section->media_id,
        'headline' => $section->headline,
        'body' => $section->body,
        'quote_text' => $section->quote_text,
        'quote_author' => $section->quote_author,
        'caption' => $section->caption,
        'url' => $section->url,
        'height' => $section->height,
        'layout' => $section->layout,
        'autoplay' => $section->autoplay,
        'is_visible' => $section->is_visible,
        'items' => $items,
      ];
    })->values();
  @endphp

  <script>
    const media = @json($editorMedia);
    const sectionLabels = @json($sectionTypes->pluck('name', 'slug'));
    const mediaUploadUrl = @json(route('admin.media.upload'));
    const csrfToken = @json(csrf_token());
    let sections = @json($editorSections);
    let selected = sections.length ? 0 : -1;

    const canvasBlocks = document.getElementById('canvasBlocks');
    const sectionInputs = document.getElementById('sectionInputs');
    const layerList = document.getElementById('layerList');
    const blockEditorPanel = document.getElementById('blockEditorPanel');
    const emptyBlockPanel = document.getElementById('emptyBlockPanel');
    const mediaLibrary = document.getElementById('mediaLibrary');
    const mediaUploadInput = document.getElementById('mediaUploadInput');
    const videoUploadInput = document.getElementById('videoUploadInput');
    const mediaUploadStatus = document.getElementById('mediaUploadStatus');
    const coverMediaSelect = document.getElementById('coverMediaSelect');
    const statusSelect = document.getElementById('statusSelect');

    const simpleFields = ['type', 'title', 'subtitle', 'variant', 'media_id', 'headline', 'body', 'quote_text', 'quote_author', 'caption', 'url', 'height', 'layout', 'autoplay', 'is_visible'];
    const itemFields = ['media_id', 'title', 'subtitle', 'value', 'label', 'time_label', 'body', 'caption', 'url'];

    function escapeHtml(value) {
      return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
    }

    function mediaUrl(id) {
      return media.find((item) => Number(item.id) === Number(id))?.url || '';
    }

    function mediaName(id) {
      return media.find((item) => Number(item.id) === Number(id))?.name || 'Chon anh';
    }

    function imageMediaItems() {
      return media.filter((item) => item.type === 'image' || !item.type);
    }

    function mediaOptions(selectedId = '', requiredType = '') {
      const available = requiredType ? media.filter((item) => item.type === requiredType) : media;
      return `<option value="">Khong chon</option>` + available.map((item) => `<option value="${item.id}" ${Number(item.id) === Number(selectedId) ? 'selected' : ''}>${escapeHtml(item.name)}</option>`).join('');
    }

    function currentSection() {
      return sections[selected] || null;
    }

    function firstImageId() {
      return imageMediaItems()[0]?.id || '';
    }

    function firstVideoId() {
      return media.find((item) => item.type === 'video')?.id || '';
    }

    function ensureItems(section) {
      section.items = Array.isArray(section.items) ? section.items : [];

      if (section.type === 'stats' && section.items.length === 0) {
        section.items = [
          { value: '4', label: 'Ngay ben nhau' },
          { value: '18', label: 'Anh chup' },
          { value: '3', label: 'Dia diem' },
          { value: '16C', label: 'Thoi tiet' },
        ];
      }

      if (['gallery_grid', 'gallery_slider'].includes(section.type) && section.items.length === 0) {
        section.items = imageMediaItems().slice(0, section.type === 'gallery_grid' ? 4 : 3).map((item) => ({
          media_id: item.id,
          caption: item.name,
        }));
      }

      if (section.type === 'timeline' && section.items.length === 0) {
        section.items = [
          { time_label: 'Ngay 1', title: 'Bat dau', body: 'Viet mot moc ky niem...', media_id: firstImageId() },
          { time_label: 'Ngay 2', title: 'Khoanh khac nho', body: 'Them noi dung cho timeline...', media_id: firstImageId() },
        ];
      }
    }

    function defaultBlock(type) {
      const label = sectionLabels[type] || type;
      const base = {
        type,
        title: label,
        subtitle: '',
        variant: '',
        media_id: firstImageId(),
        headline: '',
        body: '',
        quote_text: '',
        quote_author: '',
        caption: '',
        url: '',
        height: '',
        layout: '',
        autoplay: false,
        is_visible: true,
        items: [],
      };

      if (type === 'hero_image') {
        base.variant = 'memory_header';
        base.headline = document.querySelector('[name=title]').value || 'Mot ngay cua chung minh';
        base.height = '520px';
      }

      if (type === 'rich_text') base.body = 'Viet mot doan ngan ve ky niem nay...';
      if (type === 'quote') base.quote_text = 'Sau nay neu co met, minh lai mo nhung tam anh nay ra.';
      if (type === 'single_image') base.caption = 'Ghi chu cho tam anh nay.';
      if (type === 'image_text') base.body = 'Ke mot cau chuyen ngan di kem buc anh.';
      if (type === 'video_embed') {
        base.media_id = firstVideoId();
        base.caption = 'Ghi chu cho video nay.';
      }
      if (type === 'music') {
        base.headline = 'Our Memory Song';
        base.subtitle = 'chuaminh.vn';
      }
      if (type === 'ending') {
        base.headline = 'Cam on vi da di cung nhau';
        base.body = 'Viet mot loi ket that nhe cho bai viet.';
      }

      ensureItems(base);
      return base;
    }

    function hiddenInput(name, value) {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = name;
      input.value = value ?? '';
      sectionInputs.appendChild(input);
    }

    function renderHiddenInputs() {
      sectionInputs.innerHTML = '';
      sections.forEach((section, index) => {
        simpleFields.forEach((field) => {
          let value = section[field];
          if (['autoplay', 'is_visible'].includes(field)) value = value ? 1 : 0;
          hiddenInput(`sections[${index}][${field}]`, value);
        });

        (section.items || []).forEach((item, itemIndex) => {
          itemFields.forEach((field) => hiddenInput(`sections[${index}][items][${itemIndex}][${field}]`, item[field] ?? ''));
        });
      });
    }

    function blockPreview(section, index) {
      const active = index === selected ? 'active' : '';
      const hidden = section.is_visible === false ? 'opacity-45' : '';
      const title = section.headline || section.title || sectionLabels[section.type] || section.type;

      if (section.type === 'hero_image') {
        const image = mediaUrl(section.media_id);
        return `<button type="button" data-index="${index}" class="editor-block-card ${active} ${hidden} relative h-52 overflow-hidden bg-[#0F4C81] text-left text-white">
          ${image ? `<img src="${image}" class="absolute inset-0 h-full w-full object-cover opacity-65">` : ''}
          <span class="absolute right-3 top-3 rounded-full bg-[#e34d86] px-2 py-1 text-[10px] font-extrabold">Hero</span>
          <div class="absolute inset-x-0 bottom-0 p-5">
            <p class="mb-2 text-[11px] font-bold uppercase tracking-[0.14em] text-white/70">${escapeHtml(sectionLabels[section.type] || 'Hero')}</p>
            <h3 class="font-serif text-2xl font-bold leading-tight">${escapeHtml(title)}</h3>
          </div>
        </button>`;
      }

      if (['gallery_grid', 'gallery_slider'].includes(section.type)) {
        const ids = (section.items || []).map((item) => item.media_id).filter(Boolean);
        return `<button type="button" data-index="${index}" class="editor-block-card ${active} ${hidden} bg-white p-4 text-left">
          <div class="mb-3 flex items-center justify-between"><p class="font-bold">${escapeHtml(section.title || sectionLabels[section.type])}</p><span class="text-xs font-bold text-neutral-400">${ids.length} anh</span></div>
          <div class="grid grid-cols-3 gap-2">${ids.slice(0, 3).map((id) => `<span class="block h-20 rounded-xl bg-[#e7f2fb] bg-cover bg-center" style="background-image:url('${mediaUrl(id)}')"></span>`).join('')}</div>
        </button>`;
      }

      if (section.type === 'stats') {
        return `<button type="button" data-index="${index}" class="editor-block-card ${active} ${hidden} bg-white p-4 text-left">
          <p class="mb-3 font-bold">${escapeHtml(section.title || 'Stats')}</p>
          <div class="grid grid-cols-4 gap-2">${(section.items || []).slice(0, 4).map((item) => `<span class="rounded-xl bg-neutral-100 p-2 text-center"><b class="block text-lg">${escapeHtml(item.value || '')}</b><small class="text-[10px] text-neutral-500">${escapeHtml(item.label || '')}</small></span>`).join('')}</div>
        </button>`;
      }

      if (section.type === 'timeline') {
        return `<button type="button" data-index="${index}" class="editor-block-card ${active} ${hidden} bg-white p-4 text-left">
          <div class="mb-3 flex items-center justify-between">
            <p class="font-bold">${escapeHtml(section.title || 'Timeline')}</p>
            <span class="text-xs font-bold text-neutral-400">${(section.items || []).length} moc</span>
          </div>
          <div class="space-y-2 border-l-2 border-[#8bbce8] pl-3">${(section.items || []).slice(0, 3).map((item) => `<span class="relative flex items-center gap-2 rounded-xl bg-[#f4f8fc] p-2 before:absolute before:-left-[18px] before:h-2.5 before:w-2.5 before:rounded-full before:bg-[#e34d86]">
            ${item.media_id ? `<i class="h-10 w-10 shrink-0 rounded-lg bg-cover bg-center" style="background-image:url('${mediaUrl(item.media_id)}')"></i>` : ''}
            <span class="min-w-0"><small class="block text-[10px] font-bold text-[#285b86]">${escapeHtml(item.time_label || 'Moc moi')}</small><b class="block truncate text-xs">${escapeHtml(item.title || 'Tieu de')}</b></span>
          </span>`).join('')}</div>
        </button>`;
      }

      if (section.type === 'quote') {
        return `<button type="button" data-index="${index}" class="editor-block-card ${active} ${hidden} bg-[#fff4f8] p-5 text-left">
          <p class="font-serif text-xl leading-8">"${escapeHtml(section.quote_text || 'Trich dan...')}"</p>
          <p class="mt-3 text-sm font-bold text-[#812744]">${escapeHtml(section.quote_author || '')}</p>
        </button>`;
      }

      if (section.type === 'single_image' || section.type === 'image_text') {
        const image = mediaUrl(section.media_id);
        return `<button type="button" data-index="${index}" class="editor-block-card ${active} ${hidden} bg-white p-4 text-left">
          ${image ? `<span class="mb-3 block h-44 rounded-xl bg-cover bg-center" style="background-image:url('${image}')"></span>` : ''}
          <p class="font-bold">${escapeHtml(section.title || sectionLabels[section.type])}</p>
          <p class="mt-1 text-sm text-neutral-500">${escapeHtml(section.caption || section.body || '')}</p>
        </button>`;
      }

      if (section.type === 'video_embed') {
        const video = media.find((item) => Number(item.id) === Number(section.media_id) && item.type === 'video');
        return `<button type="button" data-index="${index}" class="editor-block-card ${active} ${hidden} bg-white p-4 text-left">
          <span class="relative mb-3 block aspect-video overflow-hidden rounded-xl bg-neutral-950">
            ${video ? `<video src="${video.url}" class="h-full w-full object-cover" muted playsinline></video>` : `<span class="grid h-full place-items-center text-sm font-bold text-white/60">Upload video</span>`}
            <span class="absolute left-3 top-3 rounded-full bg-white/90 px-2 py-1 text-[10px] font-bold text-neutral-900">Video</span>
          </span>
          <p class="font-bold">${escapeHtml(section.title || 'Video')}</p>
          <p class="mt-1 truncate text-sm text-neutral-500">${escapeHtml(section.caption || mediaName(section.media_id))}</p>
        </button>`;
      }

      return `<button type="button" data-index="${index}" class="editor-block-card ${active} ${hidden} bg-white p-4 text-left">
        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-[#812744]">${escapeHtml(sectionLabels[section.type] || section.type)}</p>
        <h3 class="mt-2 text-lg font-bold">${escapeHtml(title)}</h3>
        <p class="mt-1 line-clamp-2 text-sm text-neutral-500">${escapeHtml(section.caption || section.body || section.url || '')}</p>
      </button>`;
    }

    function renderLayers() {
      layerList.innerHTML = sections.map((section, index) => `
        <button type="button" data-layer-index="${index}" class="editor-layer ${index === selected ? 'active' : ''}">
          <span class="truncate">${index + 1}. ${escapeHtml(section.title || sectionLabels[section.type] || section.type)}</span>
          <span>${section.is_visible === false ? 'An' : 'Hien'}</span>
        </button>
      `).join('');

      layerList.querySelectorAll('[data-layer-index]').forEach((button) => {
        button.addEventListener('click', () => selectBlock(Number(button.dataset.layerIndex)));
      });
    }

    function renderCanvas() {
      canvasBlocks.innerHTML = sections.map(blockPreview).join('');
      canvasBlocks.querySelectorAll('[data-index]').forEach((button) => {
        button.addEventListener('click', () => selectBlock(Number(button.dataset.index)));
      });
    }

    function setValue(input, value) {
      if (input.type === 'checkbox') {
        input.checked = Boolean(value);
      } else {
        input.value = value ?? '';
      }
    }

    function renderItemEditors(section) {
      document.querySelectorAll('[data-items-editor]').forEach((container) => {
        const type = container.dataset.itemsEditor;
        container.innerHTML = '';
        if (section.type !== type) return;

        (section.items || []).forEach((item, index) => {
          const row = document.createElement('div');
          row.className = 'rounded-xl border border-black/10 bg-white p-3';

          if (type === 'stats') {
            row.innerHTML = `
              <div class="grid grid-cols-[1fr_1fr_auto] gap-2">
                <input data-item-field="value" data-item-index="${index}" value="${escapeHtml(item.value || '')}" class="editor-mini-input" placeholder="4">
                <input data-item-field="label" data-item-index="${index}" value="${escapeHtml(item.label || '')}" class="editor-mini-input" placeholder="Ngay ben nhau">
                <button type="button" data-remove-item="${index}" class="editor-mini-danger">Xoa</button>
              </div>`;
          } else if (['gallery_grid', 'gallery_slider'].includes(type)) {
            row.innerHTML = `
              <div class="grid gap-2">
                <select data-item-field="media_id" data-item-index="${index}" class="editor-mini-input">${mediaOptions(item.media_id, 'image')}</select>
                <input data-item-field="caption" data-item-index="${index}" value="${escapeHtml(item.caption || '')}" class="editor-mini-input" placeholder="Caption">
                <button type="button" data-remove-item="${index}" class="editor-mini-danger justify-self-start">Xoa anh</button>
              </div>`;
          } else {
            row.innerHTML = `
              <div class="grid gap-2">
                <input data-item-field="time_label" data-item-index="${index}" value="${escapeHtml(item.time_label || '')}" class="editor-mini-input" placeholder="Ngay 1 - 06:00">
                <input data-item-field="title" data-item-index="${index}" value="${escapeHtml(item.title || '')}" class="editor-mini-input" placeholder="Tieu de moc">
                <textarea data-item-field="body" data-item-index="${index}" class="editor-mini-input" rows="2" placeholder="Noi dung">${escapeHtml(item.body || '')}</textarea>
                <select data-item-field="media_id" data-item-index="${index}" class="editor-mini-input">${mediaOptions(item.media_id, 'image')}</select>
                <button type="button" data-remove-item="${index}" class="editor-mini-danger justify-self-start">Xoa moc</button>
              </div>`;
          }

          container.appendChild(row);
        });
      });

      document.querySelectorAll('[data-item-field]').forEach((input) => {
        input.addEventListener('input', updateItemFromInput);
        input.addEventListener('change', updateItemFromInput);
      });

      document.querySelectorAll('[data-remove-item]').forEach((button) => {
        button.addEventListener('click', () => {
          const section = currentSection();
          if (!section) return;
          section.items.splice(Number(button.dataset.removeItem), 1);
          renderAll(false);
        });
      });
    }

    function renderBlockPanel() {
      const section = currentSection();
      emptyBlockPanel.classList.toggle('hidden', Boolean(section));
      blockEditorPanel.classList.toggle('hidden', !section);

      if (!section) return;

      document.querySelectorAll('[data-media-select]').forEach((select) => {
        select.innerHTML = mediaOptions(section[select.dataset.bind], select.dataset.mediaType || '');
      });

      document.querySelectorAll('[data-bind]').forEach((input) => {
        setValue(input, section[input.dataset.bind]);
      });

      document.querySelectorAll('[data-block-panel]').forEach((panel) => {
        const match = panel.dataset.blockPanel === section.type || (panel.dataset.blockPanel === 'default' && !document.querySelector(`[data-block-panel="${section.type}"]`));
        panel.classList.toggle('hidden', !match);
      });

      renderItemEditors(section);
    }

    function renderAll(rerenderPanel = true) {
      renderCanvas();
      renderLayers();
      renderHiddenInputs();
      if (rerenderPanel) renderBlockPanel();
    }

    function selectBlock(index) {
      selected = index;
      renderAll();
    }

    function updateBoundInput(event) {
      const section = currentSection();
      if (!section) return;

      const input = event.target;
      const key = input.dataset.bind;
      let value = input.type === 'checkbox' ? input.checked : input.value;

      if (key === 'type' && value !== section.type) {
        const fresh = defaultBlock(value);
        Object.assign(section, fresh, {
          title: section.title || fresh.title,
          is_visible: section.is_visible,
        });
      } else {
        section[key] = value;
      }

      ensureItems(section);
      renderAll(key === 'type');
    }

    function updateItemFromInput(event) {
      const section = currentSection();
      if (!section) return;

      const input = event.target;
      const index = Number(input.dataset.itemIndex);
      const key = input.dataset.itemField;
      section.items[index][key] = input.value;
      renderAll(false);
    }

    function addItem(type) {
      const section = currentSection();
      if (!section) return;

      if (type === 'stats') section.items.push({ value: '', label: '' });
      else if (['gallery_grid', 'gallery_slider'].includes(type)) section.items.push({ media_id: firstImageId(), caption: '' });
      else section.items.push({ time_label: '', title: '', body: '', media_id: firstImageId() });

      renderAll();
    }

    function addMediaToSelected(mediaId, caption = '') {
      const section = currentSection();
      if (!section) return;
      const item = media.find((entry) => Number(entry.id) === Number(mediaId));

      if (section.type === 'video_embed' && item?.type !== 'video') {
        mediaUploadStatus.textContent = 'Block video chi nhan tep video.';
        return;
      }

      if (['gallery_grid', 'gallery_slider'].includes(section.type)) {
        if (item?.type !== 'image') {
          mediaUploadStatus.textContent = 'Gallery chi nhan anh.';
          return;
        }
        section.items.push({ media_id: Number(mediaId), caption });
      } else if (section.type === 'timeline') {
        if (item?.type !== 'image') {
          mediaUploadStatus.textContent = 'Timeline chi nhan anh.';
          return;
        }
        section.items.push({ time_label: '', title: caption, body: '', media_id: Number(mediaId) });
      } else {
        section.media_id = Number(mediaId);
      }

      renderAll();
    }

    function bindMediaButton(button) {
      button.addEventListener('click', () => addMediaToSelected(Number(button.dataset.mediaId), button.title));
    }

    function appendCoverMediaOption(item) {
      const option = document.createElement('option');
      option.value = item.id;
      option.textContent = item.name;
      coverMediaSelect.prepend(option);
    }

    function appendMediaButton(item) {
      const button = document.createElement('button');
      button.type = 'button';
      button.className = 'aspect-square overflow-hidden rounded-xl bg-neutral-100 ring-1 ring-black/5';
      button.dataset.mediaId = item.id;
      button.title = item.name || '';

      if (item.url && item.type === 'image') {
        const image = document.createElement('img');
        image.src = item.url;
        image.alt = item.name || '';
        image.className = 'h-full w-full object-cover';
        button.appendChild(image);
      } else {
        const label = document.createElement('span');
        label.className = 'grid h-full place-items-center p-2 text-center text-[11px] font-semibold text-neutral-500';
        label.textContent = item.name || 'Media';
        button.appendChild(label);
      }

      bindMediaButton(button);
      mediaLibrary.prepend(button);
    }

    document.querySelectorAll('[data-editor-tab]').forEach((tab) => {
      tab.addEventListener('click', () => {
        document.querySelectorAll('[data-editor-tab]').forEach((item) => item.classList.remove('active'));
        document.querySelectorAll('[data-tab-panel]').forEach((panel) => panel.classList.add('hidden'));
        tab.classList.add('active');
        document.querySelector(`[data-tab-panel="${tab.dataset.editorTab}"]`).classList.remove('hidden');
      });
    });

    document.querySelectorAll('[data-add-block]').forEach((button) => {
      button.addEventListener('click', () => {
        sections.push(defaultBlock(button.dataset.addBlock));
        selected = sections.length - 1;
        renderAll();
      });
    });

    document.querySelectorAll('[data-bind]').forEach((input) => {
      input.addEventListener('input', updateBoundInput);
      input.addEventListener('change', updateBoundInput);
    });

    document.querySelectorAll('[data-add-item]').forEach((button) => {
      button.addEventListener('click', () => addItem(button.dataset.addItem));
    });

    document.querySelectorAll('[data-submit-status]').forEach((button) => {
      button.addEventListener('click', () => {
        statusSelect.value = button.dataset.submitStatus;
      });
    });

    document.getElementById('addDefaultBlock').addEventListener('click', () => {
      sections.push(defaultBlock('gallery_grid'));
      selected = sections.length - 1;
      renderAll();
    });

    document.getElementById('duplicateBlock').addEventListener('click', () => {
      if (!currentSection()) return;
      sections.splice(selected + 1, 0, structuredClone(currentSection()));
      selected += 1;
      renderAll();
    });

    document.getElementById('deleteBlock').addEventListener('click', () => {
      if (selected < 0) return;
      sections.splice(selected, 1);
      selected = sections.length ? Math.min(selected, sections.length - 1) : -1;
      renderAll();
    });

    document.querySelectorAll('[data-media-id]').forEach(bindMediaButton);

    async function uploadFiles(files, sourceInput) {
      if (!files.length) return;

      mediaUploadStatus.textContent = `Dang tai ${files.length} tep...`;

      try {
        for (const file of files) {
          const formData = new FormData();
          formData.append('file', file);

          const response = await fetch(mediaUploadUrl, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
            },
            body: formData,
          });

          const payload = await response.json().catch(() => ({}));

          if (!response.ok) {
            throw new Error(payload.message || 'Upload that bai. Vui long thu lai.');
          }

          const item = {
            id: payload.media.id,
            url: payload.media.url || payload.media.display_url,
            name: payload.media.name || payload.media.original_name,
            type: payload.media.type,
          };

          media.unshift(item);
          appendMediaButton(item);
          appendCoverMediaOption(item);
          addMediaToSelected(item.id, item.name);
        }

        mediaUploadStatus.textContent = `Da tai ${files.length} tep.`;
      } catch (error) {
        mediaUploadStatus.textContent = error.message;
      } finally {
        sourceInput.value = '';
      }
    }

    mediaUploadInput?.addEventListener('change', () => {
      uploadFiles(Array.from(mediaUploadInput.files || []), mediaUploadInput);
    });

    videoUploadInput?.addEventListener('change', () => {
      uploadFiles(Array.from(videoUploadInput.files || []), videoUploadInput);
    });

    document.getElementById('mobilePreview').addEventListener('click', () => document.getElementById('canvasShell').style.width = '390px');
    document.getElementById('desktopPreview').addEventListener('click', () => document.getElementById('canvasShell').style.width = '760px');
    document.getElementById('editorForm').addEventListener('submit', renderHiddenInputs);

    window.addEventListener('DOMContentLoaded', () => {
      sections.forEach(ensureItems);
      if (window.Sortable) {
        new window.Sortable(canvasBlocks, {
          animation: 160,
          handle: '.editor-block-card',
          onEnd: (event) => {
            const [item] = sections.splice(event.oldIndex, 1);
            sections.splice(event.newIndex, 0, item);
            selected = event.newIndex;
            renderAll();
          },
        });
      }
      renderAll();
    });
  </script>
</body>
</html>
