import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

export function useLocale() {
    const page = usePage();
    const { locale: i18nLocale } = useI18n();

    const currentLocale = computed(() => page.props.locale as string);
    const availableLocales = computed(() => page.props.locales as string[]);

    const setLocale = (locale: string) => {
        fetch(route('locale.set'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            body: JSON.stringify({ locale }),
        }).then(() => {
            i18nLocale.value = locale;
            window.location.reload();
        });
    };

    return { currentLocale, availableLocales, setLocale };
}
