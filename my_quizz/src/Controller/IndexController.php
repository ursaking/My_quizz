<?php
namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Question;
use App\Entity\Quizz;
use App\Entity\Reponse;
use App\Entity\ReponseForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class IndexController extends AbstractController
{
    public function number()
    {
        $cat = $this->getDoctrine()
            ->getRepository(Categorie::class)
            ->findall();
        return $this->render(
            'user/categorie.html.twig',
            ['categorie' => $cat]
        );
    }
    public function quizz()
    {
        $chemin = $_SERVER['REQUEST_URI'];
        $path = str_replace("/index/", "", $chemin);
        $quizz = $this->getDoctrine()
            ->getRepository(Quizz::class)
            ->findby(array('categorie' => $path));
        return $this->render(
            'user/quizz.html.twig',
            ['quizz' => $quizz]
        );
    }
    private function getQuestionIndex($id_quizz)
    {
        $session = new Session();
        if (!$session->has('quizz') || $session->get('quizz') != $id_quizz
            || !$session->has('question')) {
            $session->set('quizz', $id_quizz);
            $session->set('question', 0);
        }
        return $session->get('question');
    }
    private function incrementQuestionIndex()
    {
        $session = new Session();
        if ($session->has('question')) {
            $index = $session->get('question');
            $session->set('question', $index + 1);
        } else {
            $session->set('question', 0);
        }
    }

    public function question($id, Request $request)
    {
        $index = $this->getQuestionIndex($id);
        $answer = array();
        $chemin = $_SERVER['REQUEST_URI'];
        $path = str_replace("/index/quizz/", "", $chemin);
        $question = $this->getDoctrine()
            ->getRepository(Question::class)
            ->findby(array('quizz' => $id));
        if (count($question) > $index) {
            $reponse = $this->getDoctrine()
                ->getRepository(Reponse::class)
                ->findby(array('question' => $question[$index]->getId()));
            foreach ($reponse as $value) {
                $answer[$value->getReponse()] = $value->getReponseException();
            }
            $responses = new ReponseForm();
            $form = $this->createFormBuilder($responses)
                ->add('response', ChoiceType::class, array(
                    'choices' => $answer,
                    'multiple' => false, 'expanded' => true,
                ))
                ->add('save', SubmitType::class, array('label' => 'Create Task'))
                ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $rep = $form['response']->getData();
                $ses = new Session();
                if (!$ses->has('note') || !$ses->has('quiz') || $ses->get('quiz') != $id) {
                    $ses->set('quiz', $id);
                    $ses->set('note', 0);
                    $not = 0;

                    if ($rep === true) {
                        $ses->set('note', $not + 1);
                    } else {
                        $ses->set('note', $not);
                    }
                } else {
                    $not = $ses->get('note');
                    if ($rep === true) {
                        $ses->set('note', $not + 1);
                    } else {
                        $ses->set('note', $not);
                    }
                }
                $this->incrementQuestionIndex();
                return $this->redirectToRoute('question', array('id' => $id));
            }
            return $this->render(
                'user/question.html.twig',
                ['question' => $question[$index]->getQuestion(), 'form' => $form->createView()]
            );
        }
        $ses = new Session();
        return $this->render(
            'result/result.html.twig', ['result' => $ses->get('note')]
        );
    }
}
