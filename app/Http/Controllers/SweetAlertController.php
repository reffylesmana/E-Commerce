<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SweetAlertController extends Controller
{
    /**
     * Flash a SweetAlert message to the session
     *
     * @param array $options SweetAlert options
     * @return void
     */
    public static function flash(array $options)
    {
        session()->flash('sweet_alert', $options);
    }

    /**
     * Flash a success message
     *
     * @param string $message The message to display
     * @param string|null $title The title of the alert
     * @param array $options Additional SweetAlert options
     * @return void
     */
    public static function success($message, $title = 'Berhasil', array $options = [])
    {
        $defaults = [
            'icon' => 'success',
            'title' => $title,
            'text' => $message,
            'timer' => 3000,
            'timerProgressBar' => true,
            'toast' => true,
            'position' => 'top-end',
            'showConfirmButton' => false
        ];

        self::flash(array_merge($defaults, $options));
    }

    /**
     * Flash an error message
     *
     * @param string $message The message to display
     * @param string|null $title The title of the alert
     * @param array $options Additional SweetAlert options
     * @return void
     */
    public static function error($message, $title = 'Error', array $options = [])
    {
        $defaults = [
            'icon' => 'error',
            'title' => $title,
            'text' => $message,
            'timer' => 3000,
            'timerProgressBar' => true,
            'toast' => true,
            'position' => 'top-end',
            'showConfirmButton' => false
        ];

        self::flash(array_merge($defaults, $options));
    }

    /**
     * Flash a warning message
     *
     * @param string $message The message to display
     * @param string|null $title The title of the alert
     * @param array $options Additional SweetAlert options
     * @return void
     */
    public static function warning($message, $title = 'Peringatan', array $options = [])
    {
        $defaults = [
            'icon' => 'warning',
            'title' => $title,
            'text' => $message,
            'timer' => 3000,
            'timerProgressBar' => true,
            'toast' => true,
            'position' => 'top-end',
            'showConfirmButton' => false
        ];

        self::flash(array_merge($defaults, $options));
    }

    /**
     * Flash an info message
     *
     * @param string $message The message to display
     * @param string|null $title The title of the alert
     * @param array $options Additional SweetAlert options
     * @return void
     */
    public static function info($message, $title = 'Informasi', array $options = [])
    {
        $defaults = [
            'icon' => 'info',
            'title' => $title,
            'text' => $message,
            'timer' => 3000,
            'timerProgressBar' => true,
            'toast' => true,
            'position' => 'top-end',
            'showConfirmButton' => false
        ];

        self::flash(array_merge($defaults, $options));
    }

    /**
     * Flash a confirmation dialog
     *
     * @param string $message The message to display
     * @param string $title The title of the alert
     * @param string|callable|null $confirmCallback What to do when confirmed
     * @param string|callable|null $cancelCallback What to do when canceled
     * @param array $options Additional SweetAlert options
     * @return void
     */
    public static function confirm($message, $title = 'Konfirmasi', $confirmCallback = null, $cancelCallback = null, array $options = [])
    {
        $defaults = [
            'icon' => 'question',
            'title' => $title,
            'text' => $message,
            'showCancelButton' => true,
            'confirmButtonText' => 'Ya',
            'cancelButtonText' => 'Tidak',
            'confirmButtonColor' => '#3085d6',
            'cancelButtonColor' => '#d33',
            'confirmCallback' => $confirmCallback,
            'cancelCallback' => $cancelCallback
        ];

        self::flash(array_merge($defaults, $options));
    }

    /**
     * Flash a custom SweetAlert
     *
     * @param array $options SweetAlert options
     * @return void
     */
    public static function custom(array $options)
    {
        self::flash($options);
    }
}
