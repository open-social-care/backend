<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório Analítico</title>
    <style>
        body { font-family: sans-serif; } h1, h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
<h1>Relatório Analítico: {{ $templateTitle }}</h1>
<p><strong>Período:</strong> {{ $period ?? 'Todo o período' }}</p>

@foreach ($data as $category => $insights)
    <h2>{{ ucfirst(str_replace('_', ' ', $category)) }}</h2>
    @foreach ($insights as $insightTitle => $insightData)
        @if(is_iterable($insightData) && $insightTitle !== 'documentation')
            <h3>{{ $insightTitle }}</h3>
            <table>
                <thead><tr><th>Item</th><th>Valor/Contagem</th></tr></thead>
                <tbody>
                @foreach ($insightData as $key => $value)
                    <tr><td>{{ $key }}</td><td>{{ $value }}</td></tr>
                @endforeach
                </tbody>
            </table>
        @elseif($insightTitle === 'documentation' && is_array($insightData))
            <h3>Documentação</h3>
            @foreach($insightData as $docTitle => $docData)
                <h4>{{ $docTitle }}</h4>
                <table>
                    <thead><tr><th>Item</th><th>Valor/Contagem</th></tr></thead>
                    <tbody>
                    @foreach ($docData as $key => $value)
                        <tr><td>{{ $key }}</td><td>{{ $value }}</td></tr>
                    @endforeach
                    </tbody>
                </table>
            @endforeach
        @endif
    @endforeach
@endforeach
</body>
</html>