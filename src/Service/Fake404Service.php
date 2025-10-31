<?php

namespace Tourze\Fake404Bundle\Service;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

#[Autoconfigure(public: true)]
class Fake404Service
{
    /** @var string[] */
    private array $templates = [];

    public function __construct(
        private readonly Environment $twig,
        private readonly string $templatesDir,
    ) {
        $this->loadTemplates();
    }

    private function loadTemplates(): void
    {
        if (!is_dir($this->templatesDir)) {
            return;
        }

        $finder = new Finder();
        $finder->files()->in($this->templatesDir)->name('*.html.twig');

        foreach ($finder as $file) {
            $this->templates[] = '@Fake404/pages/' . $file->getRelativePathname();
        }
    }

    public function getRandomErrorPage(): ?Response
    {
        if ([] === $this->templates) {
            return null;
        }

        $template = $this->templates[array_rand($this->templates)];
        $content = $this->twig->render($template);

        return new Response($content, Response::HTTP_NOT_FOUND);
    }
}
