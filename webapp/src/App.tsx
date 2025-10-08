import { motion } from 'framer-motion';
import { AppHeader } from './components/layout/app-header';

const App: React.FC = () => {
  return (
    <div className="min-h-screen bg-background text-foreground" data-testid="app-shell">
      <AppHeader />
      <main className="mx-auto flex w-full max-w-5xl flex-col gap-6 px-4 py-16">
        <motion.section
          initial={{ opacity: 0, y: 16 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.4, delay: 0.1 }}
          className="space-y-4"
        >
          <h1 className="text-3xl font-semibold tracking-tight sm:text-4xl">
            Fan Feed Calendar — UI Shell
          </h1>
          <p className="max-w-2xl text-muted-foreground">
            This minimal React + Vite frontend provides the foundation for building the Fan Feed Calendar
            experience. Navigate using the top bar to explore Calendar, Integrations, and Settings.
          </p>
        </motion.section>
      </main>
    </div>
  );
};

export default App;
