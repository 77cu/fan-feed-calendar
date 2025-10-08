import { motion } from 'framer-motion';
import { ThemeToggle } from '../theme/theme-toggle';
import { cn } from '../../lib/utils';

const navLinks = [
  { label: 'Calendar', href: '#calendar' },
  { label: 'Integrations', href: '#integrations' },
  { label: 'Settings', href: '#settings' }
];

export const AppHeader: React.FC = () => {
  return (
    <header className="sticky top-0 z-50 border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
      <div className="mx-auto flex h-16 w-full max-w-5xl items-center justify-between px-4">
        <div className="flex items-center gap-3">
          <motion.div
            initial={{ opacity: 0, y: -10 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.3 }}
            className="text-lg font-semibold"
          >
            Fan Feed Calendar
          </motion.div>
          <nav className="hidden items-center gap-1 text-sm font-medium sm:flex">
            {navLinks.map((link) => (
              <a
                key={link.href}
                href={link.href}
                className={cn(
                  'rounded-md px-3 py-2 text-muted-foreground transition-colors hover:bg-accent hover:text-accent-foreground'
                )}
              >
                {link.label}
              </a>
            ))}
          </nav>
        </div>
        <div className="flex items-center gap-2">
          <ThemeToggle />
        </div>
      </div>
    </header>
  );
};
