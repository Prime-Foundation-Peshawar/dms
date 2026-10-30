<?php

namespace App\Controllers\Acp;

use App\Models\EventMediaModel;
use App\Models\EventModel;

class Events extends BaseAcpController
{
    public function index()
    {
        $rows = model(EventModel::class)->orderBy('event_date', 'DESC')->findAll();

        return $this->acpView('events/index', [
            'title' => 'Events',
            'rows'  => $rows,
        ]);
    }

    public function create()
    {
        return $this->acpView('events/form', [
            'title' => 'Create event',
            'row'   => null,
            'media' => [],
        ]);
    }

    public function store()
    {
        $model = model(EventModel::class);
        $model->ensureLinkColumns();
        $title = trim((string) $this->request->getPost('title'));
        $slug  = trim((string) $this->request->getPost('slug')) ?: $this->makeSlug($title);
        $cover = $this->storeUpload('cover_image', 'events', ['jpg', 'jpeg', 'png', 'webp', 'gif']);
        [$bodyHtml, $bodyText] = $this->readDualBody();

        $id = $model->insert([
            'slug'        => $slug,
            'title'       => $title,
            'excerpt'     => trim((string) $this->request->getPost('excerpt')),
            'body_html'   => $bodyHtml,
            'body_text'   => $bodyText,
            'category'    => trim((string) $this->request->getPost('category')),
            'cover_image' => $cover,
            'event_date'  => $this->request->getPost('event_date') ?: null,
            'location'    => trim((string) $this->request->getPost('location')),
            'status'      => in_array($this->request->getPost('status'), ['published', 'draft'], true)
                ? $this->request->getPost('status') : 'draft',
        ] + $this->postedLink(), true);

        $this->attachMedia((int) $id);

        return redirect()->to(site_url('acp/events'))->with('success', 'Event created.');
    }

    public function edit(int $id)
    {
        $row = model(EventModel::class)->find($id);
        if (! $row) {
            return redirect()->to(site_url('acp/events'))->with('error', 'Event not found.');
        }

        return $this->acpView('events/form', [
            'title' => 'Edit event',
            'row'   => $row,
            'media' => model(EventMediaModel::class)->forEvent($id),
        ]);
    }

    public function update(int $id)
    {
        $model = model(EventModel::class);
        $model->ensureLinkColumns();
        $row   = $model->find($id);
        if (! $row) {
            return redirect()->to(site_url('acp/events'))->with('error', 'Event not found.');
        }

        $title = trim((string) $this->request->getPost('title'));
        $slug  = trim((string) $this->request->getPost('slug')) ?: $this->makeSlug($title);
        [$bodyHtml, $bodyText] = $this->readDualBody();
        $data  = [
            'slug'       => $slug,
            'title'      => $title,
            'excerpt'    => trim((string) $this->request->getPost('excerpt')),
            'body_html'  => $bodyHtml,
            'body_text'  => $bodyText,
            'category'   => trim((string) $this->request->getPost('category')),
            'event_date' => $this->request->getPost('event_date') ?: null,
            'location'   => trim((string) $this->request->getPost('location')),
            'status'     => in_array($this->request->getPost('status'), ['published', 'draft'], true)
                ? $this->request->getPost('status') : $row['status'],
        ] + $this->postedLink();
        $cover = $this->storeUpload('cover_image', 'events', ['jpg', 'jpeg', 'png', 'webp', 'gif']);
        if ($cover) {
            $data['cover_image'] = $cover;
        }
        $model->update($id, $data);
        $this->attachMedia($id);

        return redirect()->to(site_url('acp/events/edit/' . $id))->with('success', 'Event updated.');
    }

    public function delete(int $id)
    {
        model(EventMediaModel::class)->where('event_id', $id)->delete();
        model(EventModel::class)->delete($id);

        return redirect()->to(site_url('acp/events'))->with('success', 'Event deleted.');
    }

    public function deleteMedia(int $id)
    {
        $media = model(EventMediaModel::class)->find($id);
        if ($media) {
            model(EventMediaModel::class)->delete($id);

            return redirect()->to(site_url('acp/events/edit/' . $media['event_id']))
                ->with('success', 'Media removed.');
        }

        return redirect()->to(site_url('acp/events'));
    }

    public function reorderMedia()
    {
        $ids = $this->request->getPost('ids') ?? [];
        if (! is_array($ids)) {
            return $this->response->setStatusCode(400)->setJSON(['ok' => false]);
        }
        $media = model(EventMediaModel::class);
        $order = 0;
        foreach ($ids as $id) {
            $id = (int) $id;
            if ($id > 0) {
                $media->update($id, ['sort_order' => $order++]);
            }
        }

        return $this->response->setJSON(['ok' => true]);
    }

    private function attachMedia(int $eventId): void
    {
        $paths = $this->storeUploads('media', 'events', ['jpg', 'jpeg', 'png', 'webp', 'gif', 'pdf']);
        $media = model(EventMediaModel::class);
        $order = (int) $media->where('event_id', $eventId)->countAllResults();
        foreach ($paths as $path) {
            $media->insert([
                'event_id'   => $eventId,
                'path'       => $path,
                'caption'    => '',
                'sort_order' => $order++,
            ]);
        }
    }
}
