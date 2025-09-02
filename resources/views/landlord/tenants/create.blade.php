<!DOCTYPE html>
<html>
<head>
    <title>Create Tenant</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Create New Tenant</h1>
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('landlord.tenants.store') }}" method="POST" class="bg-white p-6 rounded shadow">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium">Tenant Name</label>
                <input type="text" name="name" id="name" class="w-full border p-2 rounded @error('name') border-red-500 @enderror" value="{{ old('name') }}">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label for="domain" class="block text-sm font-medium">Subdomain (e.g., tenant1.localhost)</label>
                <input type="text" name="domain" id="domain" class="w-full border p-2 rounded @error('domain') border-red-500 @enderror" value="{{ old('domain') }}">
                @error('domain') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label for="admin_email" class="block text-sm font-medium">Admin Email</label>
                <input type="email" name="admin_email" id="admin_email" class="w-full border p-2 rounded @error('admin_email') border-red-500 @enderror" value="{{ old('admin_email') }}">
                @error('admin_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label for="admin_password" class="block text-sm font-medium">Admin Password</label>
                <input type="password" name="admin_password" id="admin_password" class="w-full border p-2 rounded @error('admin_password') border-red-500 @enderror">
                @error('admin_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label for="admin_password_confirmation" class="block text-sm font-medium">Confirm Password</label>
                <input type="password" name="admin_password_confirmation" id="admin_password_confirmation" class="w-full border p-2 rounded">
            </div>
            <div class="mb-4">
                <label for="branch_name" class="block text-sm font-medium">Branch Name</label>
                <input type="text" name="branch_name" id="branch_name" class="w-full border p-2 rounded @error('branch_name') border-red-500 @enderror" value="{{ old('branch_name', 'Default Branch') }}">
                @error('branch_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium">Branch Address (Optional)</label>
                <input type="text" name="address" id="address" class="w-full border p-2 rounded" value="{{ old('address') }}">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium">Branch Phone (Optional)</label>
                <input type="text" name="phone" id="phone" class="w-full border p-2 rounded" value="{{ old('phone') }}">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium">Branch Email (Optional)</label>
                <input type="email" name="email" id="email" class="w-full border p-2 rounded" value="{{ old('email') }}">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Tenant</button>
        </form>
    </div>
</body>
</html>