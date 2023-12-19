<?php

namespace Utils\StaticAnalyse\ErrorFormatter;

use PHPStan\Command\ErrorFormatter\ErrorFormatter;
use PHPStan\Command\AnalysisResult;
use PHPStan\Command\Output;

final class GithubErrorFormatter implements ErrorFormatter
{
    public function formatErrors(AnalysisResult $analysisResult, Output $output) : int
    {
        $githubRepository = 'https://github.com/aliaksandr-gg/static-test';
        $githubRef = getenv('GITHUB_REF');
        $branch = str_replace('refs/heads/', '', $githubRef);

        foreach ($analysisResult->getFileSpecificErrors() as $fileSpecificError) {
            $file = $fileSpecificError->getFile();
            $line = $fileSpecificError->getLine();
            $message = $fileSpecificError->getMessage();
            $errorMessage = sprintf(
                "%s/blob/%s%s#L%d - %s",
                $githubRepository,
                $branch,
                $file,
                $line,
                $message
            );
            $errorMessage = str_replace("\n", '%0A', $errorMessage);
            $line = sprintf('::error ::%s', $errorMessage);
            $output->writeRaw($line);
            $output->writeLineFormatted('');
        }
        foreach ($analysisResult->getNotFileSpecificErrors() as $errorMessage) {
            // newlines need to be encoded
            // see https://github.com/actions/starter-workflows/issues/68#issuecomment-581479448
            $errorMessage = str_replace("\n", '%0A', $errorMessage);
            $line = sprintf('::error ::%s', $errorMessage);
            $output->writeRaw($line);
            $output->writeLineFormatted('');
        }
        foreach ($analysisResult->getWarnings() as $warning) {
            // newlines need to be encoded
            // see https://github.com/actions/starter-workflows/issues/68#issuecomment-581479448
            $warning = str_replace("\n", '%0A', $warning);
            $line = sprintf('::warning ::%s', $warning);
            $output->writeRaw($line);
            $output->writeLineFormatted('');
        }
        return $analysisResult->hasErrors() ? 1 : 0;
    }
}
