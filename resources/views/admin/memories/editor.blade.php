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
    <input type="hidden" name="sections_json" id="sectionsJson">

    <header class="sticky top-0 z-40 flex h-[72px] items-center gap-4 border-b border-black/10 bg-white/92 px-5 backdrop-blur-xl">
      <a href="{{ route('home') }}" class="text-2xl font-semibold text-[#812744]">chuaminh.vn</a>
      <span class="h-8 w-px bg-black/10"></span>
      <button type="button" class="editor-icon">↶</button>
      <button type="button" class="editor-icon">↷</button>
      <span class="h-8 w-px bg-black/10"></span>
      <button type="button" id="mobilePreview" class="editor-pill bg-white">□ Mobile</button>
      <button type="button" id="desktopPreview" class="editor-pill bg-white">□ Desktop</button>
      <span class="ml-auto text-sm font-medium text-emerald-700">□ Da luu</span>
      <a href="{{ route('memories.show', $post->slug) }}" target="_blank" class="editor-pill bg-white">□ Xem truoc</a>
      <button type="submit" onclick="document.querySelector('[name=status]').value='draft'" class="editor-pill bg-white">Luu nhap</button>
      <button type="submit" onclick="document.querySelector('[name=status]').value='published'" class="editor-pill border-[#812744] bg-[#812744] text-white">Dang bai</button>
    </header>

    <div class="grid min-h-[calc(100vh-72px)] grid-cols-[320px_minmax(420px,1fr)_360px]">
      <aside class="border-r border-black/10 bg-white">
        <div class="flex border-b border-black/10 text-sm font-semibold">
          <button type="button" data-editor-tab="templates" class="editor-tab active">Template</button>
          <button type="button" data-editor-tab="blocks" class="editor-tab">Blocks</button>
          <button type="button" data-editor-tab="media" class="editor-tab">Anh</button>
        </div>

        <div data-tab-panel="templates" class="space-y-7 p-5">
          @foreach($templates->groupBy('category') as $group => $items)
            <section>
              <h2 class="mb-3 text-sm font-semibold uppercase tracking-[0.2em] text-neutral-500">{{ $group }}</h2>
              <div class="grid grid-cols-2 gap-3">
                @foreach($items as $template)
                  @php
                    $primary = data_get($template->design_tokens, 'colors.primary', data_get($template->design_tokens, 'colors.accent', '#d84d80'));
                  @endphp
                  <label class="cursor-pointer overflow-hidden rounded-2xl border bg-white {{ $post->template_id === $template->id ? 'border-[#e34d86] ring-2 ring-[#e34d86]/15' : 'border-black/15' }}">
                    <input type="radio" name="template_id" value="{{ $template->id }}" class="sr-only" @checked($post->template_id === $template->id)>
                    <span class="grid h-24 place-items-center text-xl" style="background: {{ data_get($template->design_tokens, 'colors.background', '#f7f7f4') }}; color: {{ $primary }}">□</span>
                    <span class="block truncate px-3 py-2 text-sm font-medium">{{ $template->name }}</span>
                  </label>
                @endforeach
              </div>
            </section>
          @endforeach
        </div>

        <div data-tab-panel="blocks" class="hidden p-5">
          <div class="grid grid-cols-2 gap-3">
            @foreach($sectionTypes as $type)
              <button type="button" class="rounded-2xl border border-black/10 bg-white p-4 text-left hover:border-[#e34d86]" data-add-block="{{ $type->slug }}">
                <span class="block text-lg">□</span>
                <span class="mt-2 block text-sm font-semibold">{{ $type->name }}</span>
              </button>
            @endforeach
          </div>
        </div>

        <div data-tab-panel="media" class="hidden p-5">
          <label for="mediaUploadInput" class="mb-3 block cursor-pointer rounded-2xl border border-dashed border-[#e34d86] px-4 py-4 text-center font-semibold text-[#812744]">□ Upload anh</label>
          <input id="mediaUploadInput" type="file" accept="image/*,video/*,audio/*,application/pdf" multiple class="sr-only">
          <p id="mediaUploadStatus" class="mb-4 min-h-5 text-xs font-semibold text-neutral-500"></p>
          <div id="mediaLibrary" class="grid grid-cols-3 gap-2">
            @foreach($media as $item)
              <button type="button" class="aspect-square overflow-hidden rounded-xl bg-neutral-100 ring-1 ring-black/5" data-media-id="{{ $item->id }}" title="{{ $item->original_name }}">
                @if($item->display_url && $item->type === \App\Enums\MediaType::Image)
                  <img src="{{ $item->display_url }}" alt="{{ $item->alt }}" class="h-full w-full object-cover">
                @else
                  <span class="grid h-full place-items-center p-2 text-center text-xs font-semibold text-neutral-500">{{ $item->original_name }}</span>
                @endif
              </button>
            @endforeach
          </div>
        </div>
      </aside>

      <main class="overflow-auto p-8">
        <div id="canvasShell" class="mx-auto w-[390px] transition-all">
          <div class="overflow-hidden rounded-[28px] border border-black/15 bg-white shadow-2xl">
            <div class="flex h-9 items-center justify-between bg-white px-5 text-sm font-semibold">
              <span>9:41</span>
              <span>□ □ □</span>
            </div>
            <div id="canvas" class="min-h-[720px] bg-white"></div>
            <button type="button" id="addDefaultBlock" class="m-5 w-[calc(100%-40px)] rounded-2xl border border-dashed border-neutral-400 py-4 font-semibold text-neutral-500">□ Them block</button>
          </div>
        </div>
      </main>

      <aside class="border-l border-black/10 bg-white">
        <div class="border-b border-black/10 p-5">
          <p class="text-sm font-semibold text-blue-700">□ chuaminh.vn/memories/{{ $post->slug }}</p>
        </div>

        <div class="space-y-5 p-5">
          <label class="editor-field">
            <span>Tieu de</span>
            <input name="title" value="{{ old('title', $post->title) }}" required>
          </label>
          <label class="editor-field">
            <span>Slug</span>
            <input name="slug" value="{{ old('slug', $post->slug) }}" required>
          </label>
          <label class="editor-field">
            <span>Tom tat</span>
            <textarea name="excerpt" rows="3">{{ old('excerpt', $post->excerpt) }}</textarea>
          </label>
          <div class="grid grid-cols-2 gap-3">
            <label class="editor-field">
              <span>Ngay</span>
              <input name="memory_date" type="date" value="{{ optional($post->memory_date)->format('Y-m-d') }}">
            </label>
            <label class="editor-field">
              <span>Thoi gian</span>
              <input name="date_range" value="{{ data_get($post->settings, 'date_range') }}">
            </label>
          </div>
          <label class="editor-field">
            <span>Dia diem</span>
            <input name="location_name" value="{{ $post->location_name }}">
          </label>
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
            <select name="cover_media_id">
              <option value="">Khong chon</option>
              @foreach($media as $item)
                <option value="{{ $item->id }}" @selected($post->cover_media_id === $item->id)>{{ $item->original_name }}</option>
              @endforeach
            </select>
          </label>
          <div class="grid grid-cols-2 gap-3">
            <label class="editor-field">
              <span>Trang thai</span>
              <select name="status">
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

          <section class="rounded-3xl bg-[#f8dde8] p-4">
            <h3 class="font-semibold text-[#812744]">Nhac nen</h3>
            <label class="mt-3 flex items-center gap-2 text-sm font-medium"><input type="checkbox" name="music_enabled" value="1" @checked(data_get($post->settings, 'music.enabled'))> Bat floating player</label>
            <label class="editor-field mt-3"><span>URL</span><input name="music_url" value="{{ data_get($post->settings, 'music.url') }}"></label>
            <label class="editor-field mt-3"><span>Ten bai hat</span><input name="music_title" value="{{ data_get($post->settings, 'music.title') }}"></label>
            <label class="editor-field mt-3"><span>Ca si</span><input name="music_artist" value="{{ data_get($post->settings, 'music.artist') }}"></label>
          </section>

          <section class="rounded-3xl border border-black/10 p-4">
            <div class="mb-3 flex items-center justify-between">
              <h3 class="font-semibold">Cac blocks</h3>
              <button type="button" id="duplicateBlock" class="text-sm font-semibold text-[#e34d86]">Duplicate</button>
            </div>
            <label class="editor-field">
              <span>Block dang chon</span>
              <input id="selectedBlockTitle" placeholder="Chon block tren canvas">
            </label>
            <div class="mt-3 grid grid-cols-2 gap-3">
              <label class="editor-field">
                <span>Type</span>
                <select id="selectedBlockType">
                  @foreach($sectionTypes as $type)
                    <option value="{{ $type->slug }}">{{ $type->name }}</option>
                  @endforeach
                </select>
              </label>
              <label class="editor-field">
                <span>Variant</span>
                <input id="selectedBlockVariant" placeholder="memory_header">
              </label>
            </div>
            <label class="mt-3 flex items-center gap-2 text-sm font-semibold text-neutral-700">
              <input type="checkbox" id="selectedBlockVisible">
              Hien thi block nay
            </label>
            <label class="editor-field mt-3">
              <span>Data JSON</span>
              <textarea id="selectedBlockData" rows="9" class="font-mono text-xs"></textarea>
            </label>
            <label class="editor-field mt-3">
              <span>Style JSON</span>
              <textarea id="selectedBlockStyle" rows="5" class="font-mono text-xs"></textarea>
            </label>
            <button type="button" id="applyBlock" class="mt-3 rounded-full bg-[#812744] px-4 py-2 text-sm font-semibold text-white">Cap nhat block</button>
            <button type="button" id="deleteBlock" class="mt-3 rounded-full px-4 py-2 text-sm font-semibold text-red-600">Xoa block</button>
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

    $editorSections = $post->sections->map(fn ($section) => [
      'type' => $section->type,
      'title' => $section->title,
      'subtitle' => $section->subtitle,
      'variant' => $section->variant,
      'data' => $section->data,
      'style' => $section->style,
      'settings' => $section->settings,
      'is_visible' => $section->is_visible,
    ])->values();
  @endphp

  <script>
    const media = @json($editorMedia);
    const sectionLabels = @json($sectionTypes->pluck('name', 'slug'));
    const mediaUploadUrl = @json(route('admin.media.upload'));
    const csrfToken = @json(csrf_token());
    let sections = @json($editorSections);
    let selected = 0;

    const canvas = document.getElementById('canvas');
    const sectionsJson = document.getElementById('sectionsJson');
    const selectedTitle = document.getElementById('selectedBlockTitle');
    const selectedType = document.getElementById('selectedBlockType');
    const selectedVariant = document.getElementById('selectedBlockVariant');
    const selectedVisible = document.getElementById('selectedBlockVisible');
    const selectedData = document.getElementById('selectedBlockData');
    const selectedStyle = document.getElementById('selectedBlockStyle');
    const mediaLibrary = document.getElementById('mediaLibrary');
    const mediaUploadInput = document.getElementById('mediaUploadInput');
    const mediaUploadStatus = document.getElementById('mediaUploadStatus');
    const coverMediaSelect = document.querySelector('[name=cover_media_id]');

    function mediaUrl(id) {
      return media.find((item) => Number(item.id) === Number(id))?.url || '';
    }

    function imageMediaItems() {
      return media.filter((item) => item.type === 'image' || !item.type);
    }

    function escapeHtml(value) {
      return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
    }

    function addMediaToSelected(mediaId, caption = '') {
      const section = sections[selected];
      if (!section) return;

      if (section.type === 'gallery_grid') section.data.media_ids = [...(section.data.media_ids || []), Number(mediaId)];
      else if (section.type === 'gallery_slider') section.data.slides = [...(section.data.slides || []), { media_id: Number(mediaId), caption }];
      else section.data.media_id = Number(mediaId);

      render();
    }

    function bindMediaButton(button) {
      button.addEventListener('click', () => addMediaToSelected(Number(button.dataset.mediaId), button.title));
    }

    function appendCoverMediaOption(item) {
      if (!coverMediaSelect) return;

      const option = document.createElement('option');
      option.value = item.id;
      option.textContent = item.name;
      coverMediaSelect.prepend(option);
    }

    function appendMediaButton(item) {
      if (!mediaLibrary) return;

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
        label.className = 'grid h-full place-items-center p-2 text-center text-xs font-semibold text-neutral-500';
        label.textContent = item.name || 'Media';
        button.appendChild(label);
      }

      bindMediaButton(button);
      mediaLibrary.prepend(button);
    }

    function blockPreview(section, index) {
      const title = section.data?.headline || section.title || sectionLabels[section.type] || section.type;
      const image = section.data?.media_id ? mediaUrl(section.data.media_id) : '';
      const active = index === selected ? 'border-[#e34d86] border-dashed' : 'border-transparent';
      const hidden = section.is_visible === false ? 'opacity-45' : '';

      if (section.type === 'hero_image') {
        return `<button type="button" data-index="${index}" class="editor-block ${active} ${hidden} relative h-44 w-full overflow-hidden bg-[#0F4C81] text-left text-white">
          ${image ? `<img src="${image}" class="absolute inset-0 h-full w-full object-cover opacity-60">` : ''}
          <span class="absolute right-3 top-3 rounded-full bg-[#e34d86] px-3 py-1 text-xs font-bold">Hero</span>
          <div class="absolute inset-x-0 bottom-0 p-5"><p class="mb-2 inline-flex rounded-full bg-white/15 px-3 py-1 text-xs">□ ${escapeHtml(section.data?.tags?.[0] || 'Memory')}</p><h3 class="text-2xl font-bold">${escapeHtml(title)}</h3><p class="text-white/70">${escapeHtml(section.data?.date_range || '')}</p></div>
        </button>`;
      }

      if (section.type === 'gallery_slider' || section.type === 'gallery_grid') {
        const ids = section.type === 'gallery_grid' ? (section.data?.media_ids || []) : (section.data?.slides || []).map((slide) => slide.media_id);
        return `<button type="button" data-index="${index}" class="editor-block ${active} ${hidden} bg-white p-5 text-left"><p class="mb-3 font-semibold">${escapeHtml(section.title || sectionLabels[section.type])}</p><div class="grid grid-cols-3 gap-2">${ids.slice(0, 3).map((id) => `<span class="block h-24 rounded-2xl bg-[#e7f2fb] bg-cover bg-center" style="background-image:url('${mediaUrl(id)}')"></span>`).join('')}</div></button>`;
      }

      if (section.type === 'quote') {
        return `<button type="button" data-index="${index}" class="editor-block ${active} ${hidden} bg-[#f8dde8] p-5 text-left"><p class="font-serif text-xl">"${escapeHtml(section.data?.quote || 'Quote')}"</p><p class="mt-3 text-sm text-[#812744]">${escapeHtml(section.data?.author || '')}</p></button>`;
      }

      return `<button type="button" data-index="${index}" class="editor-block ${active} ${hidden} bg-white p-5 text-left"><p class="font-semibold">□ ${escapeHtml(sectionLabels[section.type] || section.type)}</p><p class="mt-2 text-sm text-neutral-500">${escapeHtml(title)}</p></button>`;
    }

    function render() {
      canvas.innerHTML = sections.map(blockPreview).join('');
      sectionsJson.value = JSON.stringify(sections);
      canvas.querySelectorAll('[data-index]').forEach((button) => {
        button.addEventListener('click', () => selectBlock(Number(button.dataset.index)));
      });
      selectBlock(Math.min(selected, Math.max(0, sections.length - 1)), false);
    }

    function selectBlock(index, rerender = true) {
      selected = index;
      const section = sections[selected];
      if (!section) return;
      selectedTitle.value = section.title || sectionLabels[section.type] || section.type;
      selectedType.value = section.type;
      selectedVariant.value = section.variant || '';
      selectedVisible.checked = section.is_visible !== false;
      selectedData.value = JSON.stringify(section.data || {}, null, 2);
      selectedStyle.value = JSON.stringify(section.style || {}, null, 2);
      sectionsJson.value = JSON.stringify(sections);
      if (rerender) render();
    }

    function defaultBlock(type) {
      const images = imageMediaItems();
      const firstImage = images[0]?.id || null;
      const base = { type, title: sectionLabels[type] || type, subtitle: '', variant: '', data: {}, style: {}, settings: {}, is_visible: true };
      if (type === 'hero_image') Object.assign(base, { variant: 'memory_header', style: { '--section-height': '390px' }, data: { media_id: firstImage, headline: document.querySelector('[name=title]').value, date_range: document.querySelector('[name=date_range]').value, tags: ['Du lich'] } });
      if (type === 'stats') Object.assign(base, { variant: 'mobile_row', data: { items: [['1', 'Ngay'], ['6', 'Anh'], ['24°', 'Thoi tiet'], ['1', 'Dia diem']] } });
      if (type === 'gallery_slider') Object.assign(base, { variant: 'featured_moments', style: { '--section-height': '240px' }, data: { autoplay: false, slides: images.slice(0, 3).map((item) => ({ media_id: item.id, caption: item.name })) } });
      if (type === 'gallery_grid') Object.assign(base, { variant: 'mosaic', data: { media_ids: images.slice(0, 4).map((item) => item.id), more_count: 0 } });
      if (type === 'quote') Object.assign(base, { variant: 'soft_card', data: { quote: 'Mot khoanh khac nho nhung minh se giu rat lau.', author: 'chuaminh.vn' } });
      if (type === 'rich_text') base.data = { html: '<p>Viet mot doan ngan ve ky niem nay...</p>' };
      return base;
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
        render();
      });
    });

    document.getElementById('addDefaultBlock').addEventListener('click', () => {
      sections.push(defaultBlock('gallery_grid'));
      selected = sections.length - 1;
      render();
    });

    document.getElementById('applyBlock').addEventListener('click', () => {
      try {
        sections[selected].title = selectedTitle.value;
        sections[selected].type = selectedType.value;
        sections[selected].variant = selectedVariant.value;
        sections[selected].is_visible = selectedVisible.checked;
        sections[selected].data = JSON.parse(selectedData.value || '{}');
        sections[selected].style = JSON.parse(selectedStyle.value || '{}');
        render();
        alert('updated.');
      } catch (error) {
        alert('Data hoac style JSON chua hop le.');
      }
    });

    document.getElementById('deleteBlock').addEventListener('click', () => {
      sections.splice(selected, 1);
      selected = 0;
      render();
    });

    document.getElementById('duplicateBlock').addEventListener('click', () => {
      if (!sections[selected]) return;
      sections.splice(selected + 1, 0, JSON.parse(JSON.stringify(sections[selected])));
      selected += 1;
      render();
    });

    document.querySelectorAll('[data-media-id]').forEach(bindMediaButton);

    mediaUploadInput?.addEventListener('change', async () => {
      const files = Array.from(mediaUploadInput.files || []);
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
        mediaUploadInput.value = '';
      }
    });

    document.getElementById('mobilePreview').addEventListener('click', () => document.getElementById('canvasShell').style.width = '390px');
    document.getElementById('desktopPreview').addEventListener('click', () => document.getElementById('canvasShell').style.width = '760px');
    document.getElementById('editorForm').addEventListener('submit', () => sectionsJson.value = JSON.stringify(sections));

    window.addEventListener('DOMContentLoaded', () => {
      if (window.Sortable) {
        new window.Sortable(canvas, {
          animation: 160,
          onEnd: (event) => {
            const [item] = sections.splice(event.oldIndex, 1);
            sections.splice(event.newIndex, 0, item);
            selected = event.newIndex;
            render();
          },
        });
      }
      render();
    });
  </script>
</body>
</html>
