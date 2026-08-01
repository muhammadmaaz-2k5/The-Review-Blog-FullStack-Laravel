@extends('admin.layout')

@section('title', 'Send Notification')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Send Mobile Notification</h1>
</div>

<div class="row">
    <div class="col-md-8">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if(!$hasCredentials)
        <div class="card mb-4 border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Firebase Credentials Missing</h5>
            </div>
            <div class="card-body">
                <p class="card-text">
                    To send push notifications, you must first upload your Firebase Service Account JSON file (<code>firebase_credentials.json</code>).
                    You can download this file from the Firebase Console (Project Settings > Service accounts).
                </p>
                
                <form action="{{ route('admin.notifications.upload_credentials') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="firebase_credentials" class="form-label">Upload Service Account JSON</label>
                        <input class="form-control" type="file" id="firebase_credentials" name="firebase_credentials" accept=".json" required>
                    </div>
                    <button type="submit" class="btn btn-danger">Upload Credentials</button>
                </form>
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Notification Details</h5>
                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Credentials Active</span>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.notifications.send') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required placeholder="e.g. New Drama Added!">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="body" class="form-label">Message Body *</label>
                        <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="4" required placeholder="e.g. Check out the latest episode now.">{{ old('body') }}</textarea>
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="image_url" class="form-label">Image URL (Optional)</label>
                        <input type="url" class="form-control @error('image_url') is-invalid @enderror" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/image.jpg">
                        <div class="form-text">Direct link to an image to display in the notification.</div>
                        @error('image_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> This will send a push notification to <strong>ALL</strong> users who have installed the mobile app and accepted notification permissions.
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Send Notification
                    </button>
                </form>

                <hr class="my-4">
                
                <div class="accordion" id="accordionCredentials">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Update Credentials File
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionCredentials">
                            <div class="accordion-body">
                                <form action="{{ route('admin.notifications.upload_credentials') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="input-group">
                                        <input class="form-control" type="file" id="firebase_credentials_update" name="firebase_credentials" accept=".json" required>
                                        <button type="submit" class="btn btn-outline-secondary">Update</button>
                                    </div>
                                    <div class="form-text">Upload a new JSON file to replace the current one.</div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
