<?php
require 'config.php';
requireAdmin();

$pdo = db();
$stmt = $pdo->query("SELECT id, category, company_name, founder_name, email, mobile, business_category, revenue, net_profit, net_worth, credit_facilities, udyam_registration, description, terms_accept, payment_verified, created_at FROM nominations ORDER BY created_at DESC");
$nominations = $stmt->fetchAll();
$total = count($nominations);
$paid = 0;
foreach ($nominations as $n) {
    if ((int) $n['payment_verified'] === 1) $paid++;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Nominations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Anek+Devanagari:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Anek Devanagari', sans-serif; background: #F5F1E8; color: #1A2B4D; }
        .gov-header { background: linear-gradient(180deg, #1A2B4D 0%, #2C3E5F 100%); border-bottom: 3px solid #D4AF37; }
        .gov-card { background-color: #fff; border-left: 5px solid #D4AF37; }
        .gov-button { background-color: #1A2B4D; color: #F5F1E8; border: 2px solid #D4AF37; transition: all 0.3s ease; }
        .gov-button:hover { background-color: #D4AF37; color: #1A2B4D; }
        .gov-accent { color: #D4AF37; }
    </style>
</head>
<body class="min-h-screen">
    <header class="gov-header py-4 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 flex justify-between items-center">
            <h1 class="text-xl font-bold text-white">Nomination Admin</h1>
            <a href="logout.php" class="gov-button px-4 py-2 rounded-md text-sm font-bold">Logout</a>
        </div>
    </header>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div class="gov-card rounded-md p-6">
                <div class="text-3xl font-bold gov-accent"><?php echo $total; ?></div>
                <div class="text-gray-600">Total Nominations</div>
            </div>
            <div class="gov-card rounded-md p-6">
                <div class="text-3xl font-bold gov-accent"><?php echo $paid; ?></div>
                <div class="text-gray-600">Payment Verified</div>
            </div>
        </div>
        <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-blue-900">All Nominations</h2>
                <p class="text-sm text-gray-600">Full form data for each submission. Tick Payment Verified after confirming in gateway.</p>
            </div>
            <button type="button" id="view-toggle-btn" class="gov-button px-4 py-2 rounded-md text-sm font-bold">
                Click to view list
            </button>
        </div>
        <div id="view-cards" class="space-y-6">
            <?php foreach ($nominations as $i => $n): ?>
            <div class="gov-card rounded-lg shadow-lg overflow-hidden nomination-card" data-id="<?php echo (int) $n['id']; ?>">
                <div class="bg-gray-50 px-6 py-3 flex flex-wrap items-center justify-between gap-4 border-b border-gray-200">
                    <span class="font-bold text-blue-900">#<?php echo $total - $i; ?> · <?php echo htmlspecialchars(date('d M Y, H:i', strtotime($n['created_at']))); ?></span>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="payment-checkbox w-4 h-4 rounded border-2 border-gray-300 text-yellow-600 focus:ring-yellow-500" <?php echo (int) $n['payment_verified'] === 1 ? 'checked' : ''; ?>>
                        <span class="ml-2 text-sm font-semibold">Payment Verified</span>
                    </label>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-4 text-sm">
                        <div><span class="font-semibold text-gray-500 block mb-0.5">Company Name</span><span class="text-gray-900"><?php echo htmlspecialchars($n['company_name']); ?></span></div>
                        <div><span class="font-semibold text-gray-500 block mb-0.5">Founder / CEO</span><span class="text-gray-900"><?php echo htmlspecialchars($n['founder_name']); ?></span></div>
                        <div><span class="font-semibold text-gray-500 block mb-0.5">Email</span><span class="text-gray-900"><?php echo htmlspecialchars($n['email']); ?></span></div>
                        <div><span class="font-semibold text-gray-500 block mb-0.5">Mobile</span><span class="text-gray-900"><?php echo htmlspecialchars($n['mobile']); ?></span></div>
                        <div><span class="font-semibold text-gray-500 block mb-0.5">Award Category</span><span class="text-gray-900"><?php echo htmlspecialchars($n['category']); ?></span></div>
                        <div><span class="font-semibold text-gray-500 block mb-0.5">Business Category</span><span class="text-gray-900"><?php echo htmlspecialchars($n['business_category']); ?></span></div>
                        <div><span class="font-semibold text-gray-500 block mb-0.5">Revenue (Lakhs)</span><span class="text-gray-900"><?php echo htmlspecialchars($n['revenue'] ?? '—'); ?></span></div>
                        <div><span class="font-semibold text-gray-500 block mb-0.5">Net Profit (Lakhs)</span><span class="text-gray-900"><?php echo htmlspecialchars($n['net_profit'] ?? '—'); ?></span></div>
                        <div><span class="font-semibold text-gray-500 block mb-0.5">Net Worth (Lakhs)</span><span class="text-gray-900"><?php echo htmlspecialchars($n['net_worth'] ?? '—'); ?></span></div>
                        <div><span class="font-semibold text-gray-500 block mb-0.5">Credit Facilities (Lakhs)</span><span class="text-gray-900"><?php echo htmlspecialchars($n['credit_facilities'] ?? '—'); ?></span></div>
                        <div><span class="font-semibold text-gray-500 block mb-0.5">Udyam Registration</span><span class="text-gray-900"><?php echo htmlspecialchars($n['udyam_registration'] ?? '—'); ?></span></div>
                        <div><span class="font-semibold text-gray-500 block mb-0.5">Terms Accepted</span><span class="text-gray-900"><?php echo (int)($n['terms_accept'] ?? 0) === 1 ? 'Yes' : 'No'; ?></span></div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <span class="font-semibold text-gray-500 block mb-1 text-sm">Growth Story / Description</span>
                        <p class="text-gray-900 whitespace-pre-wrap"><?php echo htmlspecialchars($n['description'] ?? '—'); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if (empty($nominations)): ?>
            <div class="gov-card rounded-lg p-12 text-center text-gray-500">No nominations yet.</div>
            <?php endif; ?>
        </div>
        <div id="view-table" class="gov-card rounded-lg shadow-lg overflow-hidden hidden">
            <?php if (empty($nominations)): ?>
            <div class="p-12 text-center text-gray-500">No nominations yet.</div>
            <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-blue-900 text-white">
                            <th class="text-left p-3 font-bold whitespace-nowrap">#</th>
                            <th class="text-left p-3 font-bold whitespace-nowrap">Date</th>
                            <th class="text-left p-3 font-bold whitespace-nowrap">Payment</th>
                            <th class="text-left p-3 font-bold whitespace-nowrap">Company Name</th>
                            <th class="text-left p-3 font-bold whitespace-nowrap">Founder / CEO</th>
                            <th class="text-left p-3 font-bold whitespace-nowrap">Email</th>
                            <th class="text-left p-3 font-bold whitespace-nowrap">Mobile</th>
                            <th class="text-left p-3 font-bold whitespace-nowrap">Award Category</th>
                            <th class="text-left p-3 font-bold whitespace-nowrap">Business Category</th>
                            <th class="text-left p-3 font-bold whitespace-nowrap">Revenue (Lakhs)</th>
                            <th class="text-left p-3 font-bold whitespace-nowrap">Net Profit (Lakhs)</th>
                            <th class="text-left p-3 font-bold whitespace-nowrap">Net Worth (Lakhs)</th>
                            <th class="text-left p-3 font-bold whitespace-nowrap">Credit (Lakhs)</th>
                            <th class="text-left p-3 font-bold whitespace-nowrap">Udyam Reg.</th>
                            <th class="text-left p-3 font-bold whitespace-nowrap">Terms</th>
                            <th class="text-left p-3 font-bold whitespace-nowrap min-w-[200px]">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($nominations as $i => $n): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50 nomination-card" data-id="<?php echo (int) $n['id']; ?>">
                            <td class="p-3 font-semibold text-blue-900"><?php echo $total - $i; ?></td>
                            <td class="p-3 whitespace-nowrap"><?php echo htmlspecialchars(date('d M Y, H:i', strtotime($n['created_at']))); ?></td>
                            <td class="p-3">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="payment-checkbox w-4 h-4 rounded border-2 border-gray-300 text-yellow-600 focus:ring-yellow-500" <?php echo (int) $n['payment_verified'] === 1 ? 'checked' : ''; ?>>
                                    <span class="ml-1 text-xs font-semibold">Verified</span>
                                </label>
                            </td>
                            <td class="p-3"><?php echo htmlspecialchars($n['company_name']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($n['founder_name']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($n['email']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($n['mobile']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($n['category']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($n['business_category']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($n['revenue'] ?? '—'); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($n['net_profit'] ?? '—'); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($n['net_worth'] ?? '—'); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($n['credit_facilities'] ?? '—'); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($n['udyam_registration'] ?? '—'); ?></td>
                            <td class="p-3"><?php echo (int)($n['terms_accept'] ?? 0) === 1 ? 'Yes' : 'No'; ?></td>
                            <td class="p-3 text-gray-700 max-w-xs whitespace-pre-wrap truncate" title="<?php echo htmlspecialchars($n['description'] ?? '—'); ?>"><?php echo htmlspecialchars(mb_substr($n['description'] ?? '—', 0, 80)); ?><?php echo (mb_strlen($n['description'] ?? '') > 80) ? '…' : ''; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </main>
    <script>
(function() {
    var apiUrl = '../api/update_payment.php';
    var viewCards = document.getElementById('view-cards');
    var viewTable = document.getElementById('view-table');
    var toggleBtn = document.getElementById('view-toggle-btn');
    var isTable = false;
    if (toggleBtn && viewCards && viewTable) {
        toggleBtn.addEventListener('click', function() {
            isTable = !isTable;
            if (isTable) {
                viewCards.classList.add('hidden');
                viewTable.classList.remove('hidden');
                toggleBtn.textContent = 'Click to view cards';
            } else {
                viewCards.classList.remove('hidden');
                viewTable.classList.add('hidden');
                toggleBtn.textContent = 'Click to view list';
            }
        });
    }
    function bindPaymentCheckboxes() {
        document.querySelectorAll('.payment-checkbox').forEach(function(cb) {
            if (cb._bound) return;
            cb._bound = true;
            cb.addEventListener('change', function() {
                var card = this.closest('.nomination-card');
                var id = card ? card.getAttribute('data-id') : null;
                if (!id) return;
                var verified = this.checked ? 1 : 0;
                var xhr = new XMLHttpRequest();
                xhr.open('POST', apiUrl);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status !== 200 || (JSON.parse(xhr.responseText) || {}).success !== true) {
                        cb.checked = !cb.checked;
                    }
                };
                xhr.send('id=' + encodeURIComponent(id) + '&verified=' + encodeURIComponent(verified));
            });
        });
    }
    bindPaymentCheckboxes();
})();
    </script>
</body>
</html>

