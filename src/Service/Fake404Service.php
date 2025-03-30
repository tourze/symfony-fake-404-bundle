<?php

namespace Tourze\Fake404Bundle\Service;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class Fake404Service
{
    private array $templates = [];

    public function __construct(
        private readonly Environment $twig,
        private readonly string $templatesDir,
    ) {
        $this->loadTemplates();
    }

    private function loadTemplates(): void
    {
        $finder = new Finder();
        $finder->files()->in($this->templatesDir)->name('*.html.twig');

        foreach ($finder as $file) {
            $this->templates[] = '@Fake404/pages/' . $file->getRelativePathname();
        }
    }

    public function getRandomErrorPage(): ?Response
    {
        if (empty($this->templates)) {
            return null;
        }

        $template = $this->templates[array_rand($this->templates)];
        $content = $this->twig->render($template);

        return new Response($content, Response::HTTP_NOT_FOUND);
    }
}
