<?php namespace App\Service\Pdf;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Response;
use Symfony\Component\Process\Process;

class PhantomPdf {

    private $path;

    public function __construct()
    {
        $this->files = new Filesystem();
    }

    /**
     * Generate PDF document and return its path
     *
     * @param $html
     * @param null $fileName
     * @return string
     */
    public function generate($html, $fileName = null)
    {
        // Write down view HTML so PhantomJS can access it
        $this->files->put($this->path = __DIR__.'/work/'.($fileName ?: md5($html)).'.pdf', $html);

        $this->getPhantomProcess()->setTimeout(10)->run();

        return $this->path;
    }

    /**
     * Generate PDF document and return it directly to browser
     *
     * @param $html
     * @param null $fileName
     * @return Response
     */
    public function stream($html, $fileName = null)
    {
        $document = $this->generate($html, $fileName);

        $response = Response::make($this->files->get($document), 200);
        $response->headers->set('Content-Type', 'application/pdf');

        $this->files->delete($document);

        return $response;
    }

    /**
     * Generate PDF document and return it to browser to download
     *
     * @param $html
     * @param null $fileName
     * @return Response
     */
    public function download($html, $fileName = null)
    {
        $response = $this->stream($html, $fileName);

        $response->headers->set('Content-Description', 'File Transfer');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.($fileName ?: md5($html)).'.pdf'.'"');
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    /**
     * Get the PhantomJS process instance.
     *
     * @return \Symfony\Component\Process\Process
     */
    private function getPhantomProcess()
    {
        $system = $this->getSystem();

        $phantom = __DIR__.'/bin/'.$system.'/phantomjs'.$this->getExtension($system);

        return new Process($phantom.' invoice.js '.$this->path, __DIR__);
    }

    /**
     * Get the operating system for the current platform.
     *
     * @throws \RuntimeException
     * @return string
     */
    protected function getSystem()
    {
        $uname = strtolower(php_uname());

        if (str_contains($uname, 'darwin'))
        {
            return 'macosx';
        }
        elseif (str_contains($uname, 'win'))
        {
            return 'windows';
        }
        elseif (str_contains($uname, 'linux'))
        {
            return PHP_INT_SIZE === 4 ? 'linux-i686' : 'linux-x86_64';
        }
        else
        {
            throw new \RuntimeException("Unknown operating system.");
        }
    }

    /**
     * Get the binary extension for the system.
     *
     * @param string $system
     * @return string
     */
    protected function getExtension($system)
    {
        return $system == 'windows' ? '.exe' : '';
    }
}
