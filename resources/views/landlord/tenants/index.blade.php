<!DOCTYPE html>
<html>
<head>
    <title>Landlord Dashboard - Tenants</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Manage Tenants</h1>
        <a href="{{ route('landlord.tenants.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Create New Tenant</a>
        <table class="min-w-full mt-4 bg-white">
            <thead>
                <tr>
                    <th class="py-2">ID</th>
                    <th class="py-2">Name</th>
                    <th class="py-2">Domain</th>
                    <th class="py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tenants as $tenant)
                    <tr>
                        <td class="border px-4 py-2">{{ $tenant->id }}</td>
                        <td class="border px-4 py-2">{{ $tenant->name }}</td>
                        <td class="border px-4 py-2"><a href="http://{{ $tenant->domain }}" target="_blank">{{ $tenant->domain }}</a></td>
                        <td class="border px-4 py-2">
                            <!-- Add edit/delete actions if needed -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>