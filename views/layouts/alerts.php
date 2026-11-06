
<?php if (isset($_SESSION['success'])): ?>
    <div class="flex items-center gap-3 p-4 mb-6 text-sm bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl animate-fade-in">
        <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0"></i>
        <div class="font-medium">
            <?php 
                echo htmlspecialchars($_SESSION['success']); 
                unset($_SESSION['success']);
            ?>
        </div>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="flex items-center gap-3 p-4 mb-6 text-sm bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded-xl animate-fade-in">
        <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
        <div class="font-medium">
            <?php 
                echo htmlspecialchars($_SESSION['error']); 
                unset($_SESSION['error']);
            ?>
        </div>
    </div>
<?php endif; ?>
