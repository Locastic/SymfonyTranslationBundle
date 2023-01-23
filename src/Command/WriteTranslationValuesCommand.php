<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Command;

use Locastic\SymfonyTranslationBundle\Provider\TranslationsProviderInterface;
use Locastic\SymfonyTranslationBundle\Saver\TranslationValueSaverInterface;
use Locastic\SymfonyTranslationBundle\Transformer\TranslationKeyToTranslationTransformerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function sprintf;

final class WriteTranslationValuesCommand extends Command
{
    /** @var string */
    public static $defaultName = 'locastic:symfony-translation:dump-all';

    private TranslationsProviderInterface $translationsProvider;

    private TranslationKeyToTranslationTransformerInterface $translationTransformer;

    private TranslationValueSaverInterface $translationValueSaver;

    private string $localeCode;

    private array $locales;

    protected OutputInterface $output;

    public function __construct(
        TranslationsProviderInterface $translationsProvider,
        TranslationKeyToTranslationTransformerInterface $translationTransformer,
        TranslationValueSaverInterface $translationValueSaver,
        string $localeCode,
        array $locales,
        string $name = null
    ) {
        parent::__construct($name);

        $this->translationsProvider = $translationsProvider;
        $this->translationTransformer = $translationTransformer;
        $this->translationValueSaver = $translationValueSaver;
        $this->localeCode = $localeCode;
        $this->locales = $locales;
    }

    protected function configure(): void
    {
        $this->setDescription('Dump all translations into files');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->writeln('Starting to dump translations', OutputInterface::VERBOSITY_NORMAL);

        $translations = $this->translationsProvider->getTranslations($this->localeCode, $this->locales);
        $translations = $this->translationTransformer->transformMultiple($translations);

        $this->translationValueSaver->saveTranslations($translations);

        return 0;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        $this->output = $output;
    }

    protected function writeLn(string $message, int $level = OutputInterface::OUTPUT_NORMAL): void
    {
        $this->output->writeln(sprintf('[%s] %s', date('Y-m-d H:i:s'), $message), $level);
    }
}
