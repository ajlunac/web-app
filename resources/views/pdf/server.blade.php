<div class="max-w-4xl mx-auto my-8 p-4 bg-white shadow-md rounded-md">
    <h1 class="text-2xl font-semibold text-blue-700 mb-4">Información del Servidor</h1>

    <div class="mb-6">
        <h2 class="text-xl font-semibold text-blue-600 mb-2">Datos</h2>
        <table class="w-full border-collapse border border-gray-400">
            <tbody>
                <tr class="border border-gray-400">
                    <td class="px-2 py-1 font-bold">ID: </td>
                    <td class="px-2 py-1">{{ $server['id'] }}</td>
                </tr>
                <tr class="border border-gray-400">
                    <td class="px-2 py-1 font-bold">Depatamento: </td>
                    <td class="px-2 py-1">{{ $server->department['name'] }}</td>
                </tr>
                <tr class="border border-gray-400">
                    <td class="px-2 py-1 font-bold">Nombre Servidor: </td>
                    <td class="px-2 py-1">{{ $server['name'] }}</td>
                </tr>
                <tr class="border border-gray-400">
                    <td class="px-2 py-1 font-bold">IP: </td>
                    <td class="px-2 py-1">{{ $server['ip'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div>
        <h2 class="text-xl font-semibold text-blue-600 mb-2">Mas detalles</h2>
        <table class="w-full border-collapse border border-gray-400">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-2 py-1 border border-gray-400 text-left">Departamento</th>
                    <th class="px-2 py-1 border border-gray-400 text-left">Nombre</th>
                    <th class="px-2 py-1 border border-gray-400 text-left">IP</th>
                </tr>
            </thead>
            <tbody>
                    <tr>
                        <td class="px-2 py-1 border border-gray-400">{{ $server->department['name'] }}</td>
                        <td class="px-2 py-1 border border-gray-400">{{ $server['name'] }}</td>
                        <td class="px-2 py-1 border border-gray-400">{{ $server['ip'] }}</td>
                    </tr>
            </tbody>
        </table>
    </div>
</div>
