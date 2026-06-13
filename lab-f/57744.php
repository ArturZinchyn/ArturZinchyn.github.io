<?php

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__.'/lib/';
    if (0 === strpos($class, $prefix)) {
        $relative = substr($class, strlen($prefix));
        $file = $baseDir.str_replace('\\', '/', $relative).'.php';
        if (file_exists($file)) {
            require $file;
        }
    }
});

session_start();

$inputFormat = $_POST['input_format'] ?? $_SESSION['last_input_format'] ?? 'csv';
$outputFormat = $_POST['output_format'] ?? $_SESSION['last_output_format'] ?? 'json';
$inputData = $_POST['input_data'] ?? $_SESSION['last_input_data'] ?? '';
$outputData = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serializer = new \App\Serializer();
    $serializer->addEncoder(new \App\Encoder\JsonEncoder());
    $serializer->addEncoder(new \App\Encoder\CsvEncoder());
    $serializer->addEncoder(new \App\Encoder\YamlEncoder());
    try {
        $outputData = $serializer->convert($inputData, $inputFormat, $outputFormat);
        $_SESSION['last_input_data'] = $inputData;
        $_SESSION['last_input_format'] = $inputFormat;
        $_SESSION['last_output_format'] = $outputFormat;
    } catch (\Exception $e) {
        $outputData = "Error: " . $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artur Zinchyn (57744) - PTW LAB F</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<form method="POST">
    <div class="container">
        <div class="column">
            <select name="input_format">
                <option value="csv" <?= $inputFormat === 'csv' ? 'selected' : '' ?>>csv</option>
                <option value="ssv" <?= $inputFormat === 'ssv' ? 'selected' : '' ?>>ssv</option>
                <option value="tsv" <?= $inputFormat === 'tsv' ? 'selected' : '' ?>>tsv</option>
                <option value="json" <?= $inputFormat === 'json' ? 'selected' : '' ?>>json</option>
                <option value="yaml" <?= $inputFormat === 'yaml' ? 'selected' : '' ?>>yaml</option>
            </select>
            <textarea name="input_data"><?= htmlspecialchars($inputData) ?></textarea>
        </div>

        <div class="column">
            <select name="output_format">
                <option value="csv" <?= $outputFormat === 'csv' ? 'selected' : '' ?>>csv</option>
                <option value="ssv" <?= $outputFormat === 'ssv' ? 'selected' : '' ?>>ssv</option>
                <option value="tsv" <?= $outputFormat === 'tsv' ? 'selected' : '' ?>>tsv</option>
                <option value="json" <?= $outputFormat === 'json' ? 'selected' : '' ?>>json</option>
                <option value="yaml" <?= $outputFormat === 'yaml' ? 'selected' : '' ?>>yaml</option>
            </select>
            <pre><?= htmlspecialchars($outputData) ?></pre>
        </div>
    </div>
    <button type="submit">Convert</button>
</form>
</body>
</html>
