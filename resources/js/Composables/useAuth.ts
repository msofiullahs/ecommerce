import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import type { User } from '@/types';

export function useAuth() {
    const page = usePage();

    const user = computed(() => page.props.auth?.user as User | null);
    const isAuthenticated = computed(() => !!user.value);

    const hasRole = (role: string) => {
        return user.value?.roles?.includes(role) ?? false;
    };

    const hasAnyRole = (roles: string[]) => {
        return roles.some(role => hasRole(role));
    };

    return { user, isAuthenticated, hasRole, hasAnyRole };
}
