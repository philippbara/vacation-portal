<?php
$pageTitle = "Dashboard";
$user = $_SESSION['user'] ?? ['username' => 'Guest'];
$usersJson = json_encode($users ?? []); // passed from PHP
?>

<?php include __DIR__ . '/components/header.php'; ?>

<link rel="stylesheet" href="/css/dashboard.css">

<div id="dashboard-app" class="page-container dashboard-page">
    
    <h1>Employees of <?= htmlspecialchars($user['username']) ?></h1>

    <div class="table-controls">
        <input type="text" v-model="search" placeholder="Search users...">
        <a href="/users/create">
            <button>+ Create User</button>
        </a>
    </div>
    
    <table class="styled-table">
        <thead>
            <tr>
                <th></th>
                <th>Username</th>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Total Vacation Requests</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr 
                v-for="user in filteredUsers" 
                :key="user.id" 
                @click="goToUser(user.id)" 
                style="cursor:pointer;"
            >
                <td 
                    v-if="user.pending_requests > 0" 
                    class="pending"
                    :title="user.pending_requests + ' pending vacation request' + (user.pending_requests > 1 ? 's' : '')"
                >
                    ⚠️ {{ user.pending_requests }}
                </td>
                <td v-else></td>
                <td>{{ user.username }}</td>
                <td>{{ user.employee_code }}</td>
                <td>{{ user.first_name }} {{ user.last_name }}</td>
                <td>{{ user.email }}</td>
                <td>{{ user.total_vacations }}</td>
                <td>
                    <button 
                        @click.stop="deleteUser(user.id)" 
                        class="dashboard-btn danger"
                    >
                        Delete
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/components/footer.php'; ?>

<!-- Vue 3 -->
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script>
const { createApp } = Vue;
const initialUsers = <?= $usersJson ?>;

createApp({
    data() {
        return {
            search: '',
            users: initialUsers || []
        };
    },
    computed: {
        filteredUsers() {
            if (!this.search) return this.users;
            const term = this.search.toLowerCase();
            return this.users.filter(u =>
                u.username.toLowerCase().includes(term) ||
                u.first_name.toLowerCase().includes(term) ||
                u.last_name.toLowerCase().includes(term)
            );
        }
    },
    methods: {
        goToUser(userId) {
            window.location.href = '/users/' + userId;
        },
        deleteUser(userId) {
            if (!confirm("Are you sure you want to delete this user?")) return;

            fetch(`/users/delete/${userId}`, { 
                method: 'POST' 
            })
            .then(res => {
                if (!res.ok) return alert(`Failed to ${action} request`);
                location.reload(); // always reload, no need for newStatus
            })
            .catch(() => alert(`Failed to ${action} request`));
        }
    }
}).mount('#dashboard-app');
</script>

<style>
.styled-table tbody tr:hover {
    background-color: #f0f8ff;
}
</style>