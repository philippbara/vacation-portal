<?php
// vacation_requests.php

$pageTitle = "Vacation Requests for " . htmlspecialchars($user['username']);
?>

<?php include __DIR__ . '/components/header.php'; ?>

<link rel="stylesheet" href="/css/dashboard.css">

<div id="vacation-requests-app" class="page-container">

    <?php if ($current_user['id'] === $user['id']): ?>
        <div class="table-controls">            
            <a href="/users/<?= $user['id'] ?>/new">
                <button type="button" class="dashboard-btn">+ New Vacation Request</button>
            </a>
        </div>
    <?php endif; ?>

    <table class="styled-table">
        <thead>
            <tr>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Submitted At</th>
                <th>Duration (weekdays)</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="r in requests" :key="r.id">
                <td>{{ r.start_date }}</td>
                <td>{{ r.end_date }}</td>
                <td>{{ r.reason }}</td>
                <td>{{ formatDate(r.created_at) }}</td>
                <td>{{ countWeekdays(r.start_date, r.end_date) }}</td>
                <td>{{ capitalize(r.status) }}</td>
                <td>
                    <!-- Employee can delete own pending requests -->
                    <template v-if="r.status === 'pending'">
                        <button 
                            v-if="currentUser.role === 'employee' && currentUser.id === user.id"
                            @click="handleRequest(r.id, 'delete', 'Are you sure?')"
                            class="dashboard-btn danger"
                        >
                            Delete
                        </button>

                        <!-- Manager/Admin can approve/reject -->
                        <template v-else-if="['manager','admin'].includes(currentUser.role)">
                            <button @click="handleRequest(r.id, 'approve', 'Are you sure?')" class="dashboard-btn success">Approve</button>
                            <button @click="handleRequest(r.id, 'reject', 'Are you sure?')" class="dashboard-btn danger">Reject</button>
                        </template>
                    </template>
                </td>
            </tr>
        </tbody>
    </table>

    <?php if (in_array($current_user['role'], ['manager', 'admin'])): ?>
        <div class="flex-links">
            <a href="/dashboard">
                <button class="dashboard-btn">Back to Dashboard</button>
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/components/footer.php'; ?>

<!-- Vue 3 -->
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
            user: <?= json_encode($user) ?>,
            currentUser: <?= json_encode($current_user) ?>,
            requests: <?= json_encode($requests) ?>,
        };
    },
    methods: {
        formatDate(dateStr) {
            const date = new Date(dateStr);
            return date.toISOString().split('T')[0];
        },
        capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        },
        countWeekdays(startDate, endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            let count = 0;
            for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
                const day = d.getDay();
                if (day >= 1 && day <= 5) count++;
            }
            return count;
        },
        handleRequest(requestId, action, message = null) {
            if (message && !confirm(message)) return;

            fetch(`/users/${this.user.id}/${requestId}/${action}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: ''
            })
            .then(res => {
                if (!res.ok) return alert(`Failed to ${action} request`);
                location.reload(); // always reload, no need for newStatus
            })
            .catch(() => alert(`Failed to ${action} request`));
        }
    }
}).mount('#vacation-requests-app');

</script>
